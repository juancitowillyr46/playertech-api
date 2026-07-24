<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Staff\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\SchemaResetter;
use App\Tests\Support\Database\TestDatabaseKernel;

final class StaffControllerTest extends TestDatabaseKernel
{
    public function testItResendsActivationForPendingStaff(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        SchemaResetter::reset($entityManager, [
            $entityManager->getClassMetadata(Academy::class),
            $entityManager->getClassMetadata(AccountUser::class),
            $entityManager->getClassMetadata(Staff::class),
        ]);

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Lima',
            null,
            null,
            null,
            null,
            'signup',
            new Address('Av. Principal 123'),
            new City('Lima'),
            null,
            AuditTrail::create('system'),
        );

        $admin = new AccountUser();
        $admin->setEmail('admin@test.local');
        $admin->setPasswordHash('hashed-password');
        $admin->setAcademyId($academy->id()->value());
        $admin->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $admin->setStatus(AccountUser::STATUS_ACTIVE);
        $admin->setFullName('Admin Test');

        $staffUser = new AccountUser();
        $staffUser->setEmail('juan@test.local');
        $staffUser->setPasswordHash('hashed-password');
        $staffUser->setAcademyId($academy->id()->value());
        $staffUser->setRole(AccountUser::ROLE_COACH);
        $staffUser->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $staffUser->setFullName('Juan Perez');
        $staffUser->markPendingActivation('activation-token-old', (new \DateTimeImmutable())->modify('+1 day'));

        $staff = Staff::create(
            StaffId::generate(),
            $academy->id(),
            $staffUser->getId(),
            AuditTrail::create('system')
        );

        $entityManager->persist($academy);
        $entityManager->persist($admin);
        $entityManager->persist($staffUser);
        $entityManager->persist($staff);
        $entityManager->flush();

        $jwtToken = $jwtManager->create($admin);

        $response = self::$kernel->handle(Request::create(
            sprintf('/api/v1/academy/staff/%s/activation/resend', $staffUser->getId()),
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtToken,
            ]
        ));

        self::assertSame(200, $response->getStatusCode());

        $entityManager->clear();
        /** @var AccountUser $reloadedUser */
        $reloadedUser = $entityManager->getRepository(AccountUser::class)->find($staffUser->getId());

        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $reloadedUser->getStatus());
        self::assertNotSame('activation-token-old', $reloadedUser->getActivationToken());
        self::assertNotNull($reloadedUser->getActivationToken());
    }
}
