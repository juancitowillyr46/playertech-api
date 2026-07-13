<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Infrastructure\Persistence\PlayerGuardianRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PlayerGuardianRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private PlayerGuardianRepository $repository;
    private AcademyId $academyId;
    private PlayerId $playerId;
    private LegalGuardianId $guardianId;

    protected function setUp(): void
    {
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->repository = new PlayerGuardianRepository($doctrine);

        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DROP TABLE IF EXISTS player_guardians, legal_guardians, players, academies');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema([
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(LegalGuardian::class),
            $this->entityManager->getClassMetadata(PlayerGuardian::class),
        ]);
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Academy::class),
            $this->entityManager->getClassMetadata(Player::class),
            $this->entityManager->getClassMetadata(LegalGuardian::class),
            $this->entityManager->getClassMetadata(PlayerGuardian::class),
        ]);

        $this->academyId = AcademyId::generate();
        $this->playerId = PlayerId::generate();
        $this->guardianId = LegalGuardianId::generate();

        $academy = Academy::create(
            $this->academyId,
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
            $this->playerId,
            $this->academyId,
            'Juan',
            'Perez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            AuditTrail::create('system'),
        );

        $guardian = LegalGuardian::create(
            $this->guardianId,
            $this->academyId,
            'Maria',
            'Lopez',
            '+51 999 111 222',
            'maria@example.com',
            'Madre',
            AuditTrail::create('system'),
        );

        $relation = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $this->academyId,
            $this->playerId,
            $this->guardianId,
            true,
            AuditTrail::create('system'),
        );

        $this->entityManager->persist($academy);
        $this->entityManager->persist($player);
        $this->entityManager->persist($guardian);
        $this->entityManager->persist($relation);
        $this->entityManager->flush();
    }

    public function testItFindsPrimaryGuardianByPlayer(): void
    {
        $primary = $this->repository->findPrimaryByPlayer($this->academyId, $this->playerId);

        self::assertNotNull($primary);
        self::assertTrue($primary->isPrimary());
        self::assertSame($this->guardianId->value(), $primary?->guardianId()->value());
        self::assertCount(1, $this->repository->findAllByPlayer($this->academyId, $this->playerId));
    }
}
