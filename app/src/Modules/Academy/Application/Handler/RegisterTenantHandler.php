<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\RegisterTenantCommand;
use App\Modules\Academy\Application\Dto\TenantSignupData;
use App\Modules\Academy\Application\Message\SendTenantActivationEmailMessage;
use App\Modules\Academy\Application\Response\AcademyView;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class RegisterTenantHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
        private string $publicUrl,
    ) {
    }

    public function __invoke(RegisterTenantCommand $command): array
    {
        $data = TenantSignupData::fromArray($command->payload);

        if (null !== $this->academyRepository->findOneByContactEmail($data->contactEmail())) {
            throw new AcademyAlreadyExistsException();
        }

        $academyId = AcademyId::generate();

        $academy = Academy::create(
            $academyId,
            $data->academyName(),
            $data->contactEmail(),
            $data->phone(),
            $data->address(),
            $data->city(),
            $data->logo(),
            AuditTrail::create(null),
        );

        $this->academyRepository->save($academy);

        $user = new AccountUser();
        $user->setEmail($data->contactEmail()->value());
        $user->setAcademyId($academyId->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, $data->plainPassword()));
        $user->markPendingActivation(
            Uuid::v4()->toRfc4122(),
            (new \DateTimeImmutable())->modify('+24 hours')
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $activationUrl = sprintf('%s/api/v1/public/tenants/activate/%s', rtrim($this->publicUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendTenantActivationEmailMessage(
            $data->contactEmail()->value(),
            $data->contactName(),
            $data->academyName()->value(),
            $activationUrl
        ));

        return [
            'academy' => AcademyView::fromAcademy($academy)->toArray(),
            'user' => [
                'email' => $user->getUserIdentifier(),
                'status' => $user->getStatus(),
                'activation_pending' => true,
            ],
        ];
    }
}
