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
use App\Modules\Academy\Domain\Academy\AcademyRegistrationSource;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Modules\Category\Domain\Exception\CategoryNotFoundException;
use App\Modules\Category\Domain\Category\OnboardingCategoryRepository;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Exception\CategoryInactiveException;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Domain\Exception\TeamAlreadyExistsException;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class RegisterTenantHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
        private OnboardingCategoryRepository $onboardingCategoryRepository,
        private TeamRepository $teamRepository,
        private VenueRepository $venueRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
        private string $publicUrl,
        private string $tenantActivationUrl,
    ) {
    }

    public function __invoke(RegisterTenantCommand $command): TenantSignupResponse
    {
        $data = $command->input;

        if (!$data->acceptedTerms || !$data->acceptedDataProcessing) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Debe aceptar términos y tratamiento de datos.');
        }

        if (null !== $this->academyRepository->findOneByContactEmail(new \App\Shared\Domain\ValueObject\Email($data->contactEmail))) {
            throw new AcademyAlreadyExistsException();
        }

        $academyId = AcademyId::generate();
        $onboardingCategoryId = $data->onboardingCategoryId;
        $teamName = new Name($data->teamName);

        $onboardingCategory = $this->onboardingCategoryRepository->findById($onboardingCategoryId);

        if (null === $onboardingCategory) {
            throw new CategoryNotFoundException();
        }

        if (!$onboardingCategory->isActive()) {
            throw new CategoryInactiveException();
        }

        $academy = Academy::create(
            $academyId,
            new \App\Shared\Domain\ValueObject\Name($data->name),
            new \App\Shared\Domain\ValueObject\Email($data->contactEmail),
            new PhoneNumber($data->phone),
            $this->normalizeCountry($data->country),
            $data->department,
            null,
            null,
            null,
            null,
            AcademyRegistrationSource::signup()->value(),
            new Address($data->address),
            new City($data->city),
            null,
            AuditTrail::create(null),
        );

        $categoryId = CategoryId::generate();
        $category = \App\Modules\Category\Domain\Category\Category::create(
            $categoryId,
            $academyId,
            $onboardingCategory->code(),
            $onboardingCategory->name(),
            $onboardingCategory->minAge(),
            $onboardingCategory->maxAge(),
            $onboardingCategory->description(),
            AuditTrail::create($data->contactEmail),
        );

        if (null !== $this->teamRepository->findOneByAcademyCategoryAndName($academyId, $categoryId, $teamName)) {
            throw new TeamAlreadyExistsException();
        }

        $this->entityManager->beginTransaction();

        try {
            $this->academyRepository->save($academy);
            $this->entityManager->persist($category);

            $venue = Venue::create(
                VenueId::generate(),
                $academyId,
                new Name('Sede principal'),
                new Address($data->address),
                new City($data->city),
                $data->country,
                $data->department,
                new PhoneNumber($data->phone),
                null,
                true,
                AuditTrail::create($data->contactEmail),
            );

            $this->venueRepository->save($venue);

            $user = new AccountUser();
            $user->setFullName($data->contactName);
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
            $this->entityManager->flush();
            $this->entityManager->commit();

            $activationUrl = sprintf('%s?token=%s', rtrim($this->tenantActivationUrl, '/'), $user->getActivationToken());

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
        } catch (\Throwable $throwable) {
            if ($this->entityManager->getConnection()->isTransactionActive()) {
                $this->entityManager->rollback();
            }

            throw $throwable;
        }
    }

    private function normalizeCountry(?string $country): string
    {
        $normalized = trim((string) $country);

        return '' === $normalized ? 'Colombia' : $normalized;
    }
}
