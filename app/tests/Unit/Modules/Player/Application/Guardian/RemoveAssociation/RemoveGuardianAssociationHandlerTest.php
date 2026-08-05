<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Guardian\RemoveAssociation;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Application\Guardian\RemoveAssociation\RemoveGuardianAssociationCommand;
use App\Modules\Player\Application\Guardian\RemoveAssociation\RemoveGuardianAssociationHandler;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class RemoveGuardianAssociationHandlerTest extends TestCase
{
    public function testItPromotesTheMostRecentNonPrimaryGuardianWhenRemovingTheCurrentPrimary(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $removedGuardianId = LegalGuardianId::generate();
        $olderGuardianId = LegalGuardianId::generate();
        $newestGuardianId = LegalGuardianId::generate();

        $player = $this->createPlayer($academyId, $playerId);
        $removedGuardian = $this->createGuardian($academyId, $removedGuardianId);
        $olderGuardian = $this->createGuardian($academyId, $olderGuardianId);
        $newestGuardian = $this->createGuardian($academyId, $newestGuardianId);

        $primaryRelation = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $academyId,
            $playerId,
            $removedGuardianId,
            true,
            AuditTrail::create('actor-id')
        );

        $olderRelation = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $academyId,
            $playerId,
            $olderGuardianId,
            false,
            AuditTrail::create('actor-id')
        );

        $newestRelation = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $academyId,
            $playerId,
            $newestGuardianId,
            false,
            AuditTrail::create('actor-id')
        );

        $playerRepository = $this->createMock(PlayerRepository::class);
        $playerRepository->method('findById')->willReturn($player);

        $guardianRepository = $this->createMock(LegalGuardianRepository::class);
        $guardianRepository->method('findById')->willReturnCallback(
            static fn (AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian => match ($guardianId->value()) {
                $removedGuardianId->value() => $removedGuardian,
                $olderGuardianId->value() => $olderGuardian,
                $newestGuardianId->value() => $newestGuardian,
                default => null,
            }
        );

        $repository = new class($primaryRelation, $olderRelation, $newestRelation) implements PlayerGuardianRepository {
            public array $relations = [];
            public ?PlayerGuardian $removed = null;
            public ?PlayerGuardian $saved = null;

            public function __construct(PlayerGuardian ...$relations)
            {
                foreach ($relations as $relation) {
                    $this->relations[$relation->id()->value()] = $relation;
                }
            }

            public function save(PlayerGuardian $playerGuardian): void
            {
                $this->relations[$playerGuardian->id()->value()] = $playerGuardian;
                $this->saved = $playerGuardian;
            }

            public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian
            {
                return $this->relations[$playerGuardianId->value()] ?? null;
            }

            public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian
            {
                foreach ($this->relations as $relation) {
                    if ($relation->playerId()->equals($playerId) && $relation->guardianId()->equals($guardianId)) {
                        return $relation;
                    }
                }

                return null;
            }

            public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
            {
                return array_values(array_filter(
                    $this->relations,
                    static fn (PlayerGuardian $relation): bool => $relation->playerId()->equals($playerId)
                ));
            }

            public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array
            {
                return [];
            }

            public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian
            {
                foreach ($this->relations as $relation) {
                    if ($relation->playerId()->equals($playerId) && $relation->isPrimary()) {
                        return $relation;
                    }
                }

                return null;
            }

            public function remove(PlayerGuardian $playerGuardian): void
            {
                $this->removed = $playerGuardian;
                unset($this->relations[$playerGuardian->id()->value()]);
            }
        };

        $handler = new RemoveGuardianAssociationHandler($playerRepository, $guardianRepository, $repository);

        $handler(new RemoveGuardianAssociationCommand('actor-id', $academyId, $playerId, $removedGuardianId));

        self::assertSame($removedGuardianId->value(), $repository->removed?->guardianId()->value());
        self::assertTrue($repository->saved?->isPrimary());
        self::assertSame($newestGuardianId->value(), $repository->saved?->guardianId()->value());
        self::assertFalse($olderRelation->isPrimary());
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
