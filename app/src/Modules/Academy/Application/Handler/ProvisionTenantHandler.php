<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\ProvisionTenantCommand;
use App\Modules\Academy\Application\Message\SendTenantActivationEmailMessage;
use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Application\Response\TenantSignupResponse;
use App\Modules\Academy\Application\Response\TenantSignupUserResponse;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Exception\CategoryInactiveException;
use App\Modules\Identity\Application\Handler\AbstractUserHandler;
use App\Modules\Identity\Domain\Exception\UserAlreadyExistsException;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Domain\Exception\TeamAlreadyExistsException;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ProvisionTenantHandler extends AbstractUserHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
        private CategoryFinder $categoryFinder,
        private TeamRepository $teamRepository,
        EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
        private string $publicUrl,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(ProvisionTenantCommand $command): TenantSignupResponse
    {
        $data = $command->input;

        $academyContactEmail = new Email($data->contactEmail);
        $adminEmail = new Email($data->adminEmail);
        $academyId = AcademyId::generate();
        $categoryId = new CategoryId($data->categoryId);
        $teamName = new Name($data->teamName);

        if (null !== $this->academyRepository->findOneByContactEmail($academyContactEmail)) {
            throw new AcademyAlreadyExistsException();
        }

        if (null !== $this->findUserByEmail($adminEmail->value())) {
            throw new UserAlreadyExistsException();
        }

        $academy = Academy::create(
            $academyId,
            new Name($data->name),
            $academyContactEmail,
            null === $data->phone ? null : new PhoneNumber($data->phone),
            $this->normalizeCountry($data->country),
            $data->department,
            null === $data->address ? null : new Address($data->address),
            null === $data->city ? null : new City($data->city),
            null,
            AuditTrail::create($command->actorId),
        );

        $category = $this->categoryFinder->findOrFail($academyId, $categoryId);

        if ($category->status()->isInactive()) {
            throw new CategoryInactiveException();
        }

        if (null !== $this->teamRepository->findOneByAcademyCategoryAndName($academyId, $categoryId, $teamName)) {
            throw new TeamAlreadyExistsException();
        }

        $this->academyRepository->save($academy);

        $user = new AccountUser();
        $user->setFullName((string) $data->adminName);
        $user->setEmail($adminEmail->value());
        $user->setAcademyId($academyId->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, Uuid::v4()->toRfc4122()));
        $user->markPendingActivation(
            Uuid::v4()->toRfc4122(),
            (new \DateTimeImmutable())->modify('+24 hours')
        );

        $this->entityManager->persist($user);

        $team = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            $teamName,
            AuditTrail::create($command->actorId),
        );

        $this->teamRepository->save($team);

        $activationUrl = sprintf('%s/api/v1/public/tenants/activate/%s', rtrim($this->publicUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendTenantActivationEmailMessage(
            $adminEmail->value(),
            (string) $data->adminName,
            $data->name,
            $activationUrl
        ));

        return new TenantSignupResponse(
            AcademyResponse::fromAcademy($academy),
            new TenantSignupUserResponse(
                $user->getUserIdentifier(),
                $user->getStatus(),
                true,
            ),
            TeamResponse::fromTeam($team),
        );
    }

    private function normalizeCountry(?string $country): string
    {
        $normalized = trim((string) $country);

        return '' === $normalized ? 'Colombia' : $normalized;
    }
}
