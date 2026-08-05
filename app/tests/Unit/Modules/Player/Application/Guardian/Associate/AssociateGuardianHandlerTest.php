<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Guardian\Associate;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianCommand;
use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianHandler;
use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianInput;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class AssociateGuardianHandlerTest extends TestCase
{
    public function testItForcesTheFirstGuardianAsPrimaryEvenIfFalseIsSent(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $guardianId = LegalGuardianId::generate();

        $player = $this->createPlayer($academyId, $playerId);
        $guardian = $this->createGuardian($academyId, $guardianId);

        $playerRepository = $this->createMock(PlayerRepository::class);
        $playerRepository->expects(self::once())
            ->method('findById')
            ->with($academyId, $playerId)
            ->willReturn($player);

        $guardianRepository = $this->createMock(LegalGuardianRepository::class);
        $guardianRepository->expects(self::once())
            ->method('findById')
            ->with($academyId, $guardianId)
            ->willReturn($guardian);

        $savedRelations = [];
        $playerGuardianRepository = new class($savedRelations) implements PlayerGuardianRepository {
            public array $savedRelations = [];

            public function __construct(array &$savedRelations)
            {
                $this->savedRelations =& $savedRelations;
            }

            public function save(PlayerGuardian $playerGuardian): void
            {
                $this->savedRelations[$playerGuardian->id()->value()] = $playerGuardian;
            }

            public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian
            {
                return $this->savedRelations[$playerGuardianId->value()] ?? null;
            }

            public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian
            {
                return null;
            }

            public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
            {
                return [];
            }

            public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array
            {
                return [];
            }

            public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian
            {
                return null;
            }

            public function remove(PlayerGuardian $playerGuardian): void
            {
            }
        };

        $handler = new AssociateGuardianHandler($playerRepository, $guardianRepository, $playerGuardianRepository);

        $response = $handler(new AssociateGuardianCommand(
            'actor-id',
            $academyId,
            $playerId,
            new AssociateGuardianInput($guardianId->value(), false),
        ));

        self::assertTrue($response->toArray()['isPrimary']);
        self::assertCount(1, $playerGuardianRepository->savedRelations);
        self::assertTrue(array_values($playerGuardianRepository->savedRelations)[0]->isPrimary());
    }

    public function testItRespectsRequestedPrimaryFlagWhenThePlayerAlreadyHasGuardians(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $firstGuardianId = LegalGuardianId::generate();
        $secondGuardianId = LegalGuardianId::generate();

        $player = $this->createPlayer($academyId, $playerId);
        $firstGuardian = $this->createGuardian($academyId, $firstGuardianId);
        $secondGuardian = $this->createGuardian($academyId, $secondGuardianId);

        $playerRepository = $this->createMock(PlayerRepository::class);
        $playerRepository->method('findById')->willReturn($player);

        $guardianRepository = $this->createMock(LegalGuardianRepository::class);
        $guardianRepository->method('findById')
            ->willReturnCallback(static fn (AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian => match ($guardianId->value()) {
                $firstGuardianId->value() => $firstGuardian,
                $secondGuardianId->value() => $secondGuardian,
                default => null,
            });

        $existingRelation = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $academyId,
            $playerId,
            $firstGuardianId,
            true,
            AuditTrail::create('actor-id')
        );

        $savedRelations = [];
        $playerGuardianRepository = new class($existingRelation, $savedRelations) implements PlayerGuardianRepository {
            private PlayerGuardian $existingRelation;

            public array $savedRelations = [];

            public function __construct(PlayerGuardian $existingRelation, array &$savedRelations)
            {
                $this->existingRelation = $existingRelation;
                $this->savedRelations =& $savedRelations;
            }

            public function save(PlayerGuardian $playerGuardian): void
            {
                $this->savedRelations[$playerGuardian->id()->value()] = $playerGuardian;
            }

            public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian
            {
                return $this->savedRelations[$playerGuardianId->value()] ?? ($this->existingRelation->id()->value() === $playerGuardianId->value() ? $this->existingRelation : null);
            }

            public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian
            {
                return $this->existingRelation->guardianId()->equals($guardianId) ? $this->existingRelation : null;
            }

            public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
            {
                return [$this->existingRelation];
            }

            public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array
            {
                return [];
            }

            public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian
            {
                return $this->existingRelation;
            }

            public function remove(PlayerGuardian $playerGuardian): void
            {
            }
        };

        $handler = new AssociateGuardianHandler($playerRepository, $guardianRepository, $playerGuardianRepository);

        $response = $handler(new AssociateGuardianCommand(
            'actor-id',
            $academyId,
            $playerId,
            new AssociateGuardianInput($secondGuardianId->value(), false),
        ));

        self::assertFalse($response->toArray()['isPrimary']);
        self::assertCount(1, $playerGuardianRepository->savedRelations);
        self::assertFalse(array_values($playerGuardianRepository->savedRelations)[0]->isPrimary());
    }

    private function createPlayer(AcademyId $academyId, PlayerId $playerId): Player
    {
        return Player::create(
            $playerId,
            $academyId,
            'CC',
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('actor-id')
        );
    }

    private function createGuardian(AcademyId $academyId, LegalGuardianId $guardianId): LegalGuardian
    {
        return LegalGuardian::create(
            $guardianId,
            $academyId,
            'Maria',
            'Lopez',
            '+51 999 111 222',
            'maria@example.com',
            'CC',
            '12345678',
            null,
            'Madre',
            AuditTrail::create('actor-id')
        );
    }
}
