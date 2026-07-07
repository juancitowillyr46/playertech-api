<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\RegisterTenantCommand;
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
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Domain\Exception\TeamAlreadyExistsException;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class RegisterTenantHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
        private CategoryFinder $categoryFinder,
        private TeamRepository $teamRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
        private string $publicUrl,
    ) {
    }

    public function __invoke(RegisterTenantCommand $command): TenantSignupResponse
    {
        $data = $command->input;

        if (null !== $this->academyRepository->findOneByContactEmail(new \App\Shared\Domain\ValueObject\Email($data->contactEmail))) {
            throw new AcademyAlreadyExistsException();
        }

        $academyId = AcademyId::generate();
        $categoryId = new CategoryId($data->categoryId);
        $teamName = new Name($data->teamName);

        $academy = Academy::create(
            $academyId,
            new \App\Shared\Domain\ValueObject\Name($data->name),
            new \App\Shared\Domain\ValueObject\Email($data->contactEmail),
            null === $data->phone ? null : new \App\Shared\Domain\ValueObject\PhoneNumber($data->phone),
            null === $data->address ? null : new \App\Shared\Domain\ValueObject\Address($data->address),
            null === $data->city ? null : new \App\Shared\Domain\ValueObject\City($data->city),
            null,
            AuditTrail::create(null),
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
        $user->setEmail($data->contactEmail);
        $user->setAcademyId($academyId->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, $data->password));
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
            AuditTrail::create($data->contactEmail),
        );

        $this->teamRepository->save($team);

        $activationUrl = sprintf('%s/api/v1/public/tenants/activate/%s', rtrim($this->publicUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendTenantActivationEmailMessage(
            $data->contactEmail,
            $data->contactName,
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
}
