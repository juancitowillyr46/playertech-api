<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Application\Command\InviteUserCommand;
use App\Modules\Identity\Application\Message\SendUserPasswordAndActivationEmailMessage;
use App\Modules\Identity\Application\Handler\InviteUserHandler;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Command\CreateStaffMemberCommand;
use App\Modules\Staff\Application\Response\StaffOnboardingResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class CreateStaffMemberHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InviteUserHandler $inviteUserHandler,
        private MessageBusInterface $messageBus,
        private UserPasswordHasherInterface $passwordHasher,
        private StaffRepository $staffRepository,
        private string $authFrontendUrl,
    ) {
    }

    public function __invoke(CreateStaffMemberCommand $command): StaffOnboardingResponse
    {
        $input = $command->input;

        if (null === $input->email || null === $input->fullName || null === $input->role) {
            throw new BadRequestHttpException('Los datos del miembro de staff son obligatorios.');
        }

        if (!Uuid::isValid($command->academyId)) {
            throw new BadRequestHttpException('La academia no es válida.');
        }

        return $this->entityManager->wrapInTransaction(function () use ($command, $input): StaffOnboardingResponse {
            $userResponse = $input->sendInvitation
                ? ($this->inviteUserHandler)(new InviteUserCommand(
                    $command->actorId,
                    new \App\Modules\Identity\Application\Dto\InviteUserInput(
                        $input->fullName,
                        $input->email,
                        $input->role,
                        $command->academyId,
                    ),
                    $command->academyId,
                ))
                : $this->createUserWithPasswordAndActivation(
                    $command->actorId,
                    $command->academyId,
                    $input->fullName,
                    $input->email,
                    $input->role,
                );

            $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy(['email' => $input->email]);

            if (!$user instanceof AccountUser) {
                throw new BadRequestHttpException('No se pudo crear el usuario asociado al staff.');
            }

            if (null !== $this->staffRepository->findByUserId(new \App\Modules\Academy\Domain\Academy\AcademyId($command->academyId), $user->getId())) {
                throw new BadRequestHttpException('El miembro de staff ya existe.');
            }

            $staff = Staff::create(
                StaffId::generate(),
                new \App\Modules\Academy\Domain\Academy\AcademyId($command->academyId),
                $user->getId(),
                AuditTrail::create($command->actorId),
            );

            $this->staffRepository->save($staff);

            return new StaffOnboardingResponse(
                $userResponse,
                \App\Modules\Staff\Application\Response\StaffResponse::fromStaff($staff),
                $input->sendInvitation ? 'INVITATION' : 'PASSWORD',
            );
        });
    }

    private function createUserWithPasswordAndActivation(
        string $actorId,
        string $academyId,
        string $fullName,
        string $email,
        string $role,
    ): \App\Modules\Identity\Application\Response\UserResponse {
        /** @var AccountUser|null $existingUser */
        $existingUser = $this->entityManager->getRepository(AccountUser::class)->findOneBy(['email' => $email]);

        if ($existingUser instanceof AccountUser) {
            throw new \App\Modules\Identity\Domain\Exception\UserAlreadyExistsException();
        }

        if (!Uuid::isValid($academyId)) {
            throw new \App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException();
        }

        $password = $this->generatePassword();

        $user = new AccountUser();
        $user->setFullName($fullName);
        $user->setEmail($email);
        $user->setAcademyId($academyId);
        $user->setRole($role);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, $password));
        $user->markPendingActivation(Uuid::v4()->toRfc4122(), (new \DateTimeImmutable())->modify('+24 hours'));
        $user->setCreatedBy($actorId);
        $user->setUpdatedAt(null);
        $user->setUpdatedBy(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $activationUrl = sprintf('%s/activate-account/%s', rtrim($this->authFrontendUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendUserPasswordAndActivationEmailMessage(
            $email,
            $fullName,
            $email,
            $password,
            $activationUrl
        ));

        return \App\Modules\Identity\Application\Response\UserResponse::fromUser($user);
    }

    private function generatePassword(): string
    {
        return sprintf(
            '%s%s%s',
            strtoupper(bin2hex(random_bytes(2))),
            bin2hex(random_bytes(3)),
            strtoupper(bin2hex(random_bytes(2)))
        );
    }
}
