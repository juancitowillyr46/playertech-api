<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Player\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

final class PlayerGuardianControllerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private string $jwtToken;
    private string $playerId;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $doctrine = $container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');

        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DROP TABLE IF EXISTS player_guardians, legal_guardians, players, academies, users');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema([
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(AccountUser::class),
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(LegalGuardian::class),
            $this->entityManager->getClassMetadata(PlayerGuardian::class),
        ]);
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(AccountUser::class),
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(LegalGuardian::class),
            $this->entityManager->getClassMetadata(PlayerGuardian::class),
        ]);

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Cundinamarca',
            'signup',
            new Address('Av. Principal 123'),
            new City('Lima'),
            null,
            AuditTrail::create('system'),
        );

        $player = Player::create(
            \App\Modules\Player\Domain\Player\PlayerId::generate(),
            $academy->id(),
            'Juan',
            'Perez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            AuditTrail::create('system'),
        );

        $user = new AccountUser();
        $user->setEmail('coach@test.local');
        $user->setPasswordHash('hashed-password');
        $user->setAcademyId($academy->id()->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setFullName('Coach Test');

        $this->entityManager->persist($academy);
        $this->entityManager->persist($player);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->playerId = $player->id()->value();
        $this->jwtToken = $jwtManager->create($user);
    }

    public function testItAssociatesChangesAndRemovesGuardians(): void
    {
        $guardianResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/guardians',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'firstName' => 'Maria',
                'lastName' => 'Lopez',
                'phone' => '+51 999 111 222',
                'email' => 'maria@example.com',
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(201, $guardianResponse->getStatusCode());
        $guardianId = json_decode($guardianResponse->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['id'];

        $associateResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/players/'.$this->playerId.'/guardians',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'guardianId' => $guardianId,
                'isPrimary' => true,
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(201, $associateResponse->getStatusCode());
        self::assertTrue(json_decode($associateResponse->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['isPrimary']);

        $secondGuardianResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/guardians',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'firstName' => 'Carlos',
                'lastName' => 'Rojas',
                'phone' => '+51 999 333 444',
                'email' => 'carlos@example.com',
            ], JSON_THROW_ON_ERROR)
        ));

        $secondGuardianId = json_decode($secondGuardianResponse->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['id'];

        $secondAssociationResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/players/'.$this->playerId.'/guardians',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'guardianId' => $secondGuardianId,
                'isPrimary' => false,
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(201, $secondAssociationResponse->getStatusCode());

        $changePrimaryResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/players/'.$this->playerId.'/guardians/'.$secondGuardianId.'/primary',
            'PATCH',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
            ]
        ));

        self::assertSame(200, $changePrimaryResponse->getStatusCode());
        self::assertTrue(json_decode($changePrimaryResponse->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['isPrimary']);

        $removeResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/players/'.$this->playerId.'/guardians/'.$guardianId,
            'DELETE',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->jwtToken,
            ]
        ));

        self::assertSame(204, $removeResponse->getStatusCode());
    }
}
