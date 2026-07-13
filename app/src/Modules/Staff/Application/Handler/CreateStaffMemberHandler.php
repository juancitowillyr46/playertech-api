<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Application\Command\CreateUserCommand;
use App\Modules\Identity\Application\Command\InviteUserCommand;
use App\Modules\Identity\Application\Handler\CreateUserHandler;
use App\Modules\Identity\Application\Handler\InviteUserHandler;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Command\CreateStaffMemberCommand;
use App\Modules\Staff\Application\Response\StaffOnboardingResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class CreateStaffMemberHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CreateUserHandler $createUserHandler,
        private InviteUserHandler $inviteUserHandler,
        private StaffRepository $staffRepository,
    ) {
    }

    public function __invoke(CreateStaffMemberCommand $command): StaffOnboardingResponse
    {
        $input = $command->input;

        if (!$input->sendInvitation && (null === $input->password || null === $input->passwordConfirmation)) {
            throw new BadRequestHttpException('Las contraseñas son obligatorias cuando no se envía invitación.');
        }

        if (!$input->sendInvitation && $input->password !== $input->passwordConfirmation) {
            throw new BadRequestHttpException('Las contraseñas no coinciden.');
        }

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
                : ($this->createUserHandler)(new CreateUserCommand(
                    $command->actorId,
                    new \App\Modules\Identity\Application\Dto\CreateUserInput(
                        $input->fullName,
                        $input->email,
                        $input->password,
                        $input->role,
                        $command->academyId,
                    ),
                    $command->academyId,
                ));

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
}
