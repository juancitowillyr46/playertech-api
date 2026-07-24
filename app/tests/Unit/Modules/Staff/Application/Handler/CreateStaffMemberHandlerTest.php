<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Staff\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Application\Command\InviteUserCommand;
use App\Modules\Identity\Application\Handler\InviteUserHandler;
use App\Modules\Identity\Application\Message\SendUserPasswordAndActivationEmailMessage;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Command\CreateStaffMemberCommand;
use App\Modules\Staff\Application\Dto\CreateStaffMemberInput;
use App\Modules\Staff\Application\Handler\CreateStaffMemberHandler;
use App\Modules\Staff\Application\Response\StaffOnboardingResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateStaffMemberHandlerTest extends TestCase
{
    public ?object $lastMessage = null;

    public function testItCreatesPendingUserWithGeneratedPasswordAndActivationEmail(): void
    {
        $existingUsers = [];
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('findOneBy')->willReturnCallback(static function (array $criteria) use (&$existingUsers) {
            return $existingUsers[$criteria['email']] ?? null;
        });
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->method('wrapInTransaction')->willReturnCallback(static function (callable $callback) {
            return $callback();
        });
        $entityManager->expects(self::once())->method('persist')->with(self::isInstanceOf(AccountUser::class))->willReturnCallback(static function (object $entity) use (&$existingUsers): void {
            if ($entity instanceof AccountUser) {
                $existingUsers[$entity->getUserIdentifier()] = $entity;
            }
        });
        $entityManager->expects(self::once())->method('flush');

        $staffRepository = $this->createMock(StaffRepository::class);
        $staffRepository->expects(self::once())->method('findByUserId')->willReturn(null);
        $staffRepository->expects(self::once())->method('save')->with(self::isInstanceOf(Staff::class));

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed-password');

        $messageBus = new class($this) implements MessageBusInterface {
            public function __construct(private CreateStaffMemberHandlerTest $test)
            {
            }

            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->test->lastMessage = $message;

                return new Envelope($message);
            }
        };

        $handler = new CreateStaffMemberHandler(
            $entityManager,
            new InviteUserHandler(
                $entityManager,
                $passwordHasher,
                new UserAdministrationPolicy(),
                $messageBus,
                'http://localhost:4200'
            ),
            $messageBus,
            $passwordHasher,
            $staffRepository,
            'http://localhost:4200'
        );

        $response = $handler(new CreateStaffMemberCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d00',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreateStaffMemberInput(
                'Juan Perez',
                'juan@test.local',
                AccountUser::ROLE_COACH,
                null,
                null,
                false
            )
        ));

        self::assertInstanceOf(StaffOnboardingResponse::class, $response);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $response->user->toArray()['status']);
        self::assertSame('PASSWORD', $response->accessMode);
        self::assertInstanceOf(SendUserPasswordAndActivationEmailMessage::class, $this->lastMessage);
        self::assertSame('juan@test.local', $this->lastMessage->username);
        self::assertNotEmpty($this->lastMessage->password);
        self::assertStringStartsWith('http://localhost:4200/activate-account/', $this->lastMessage->activationUrl);
    }
}
