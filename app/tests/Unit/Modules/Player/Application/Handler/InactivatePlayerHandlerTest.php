<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\ActivatePlayerCommand;
use App\Modules\Player\Application\Command\InactivatePlayerCommand;
use App\Modules\Player\Application\Handler\ActivatePlayerHandler;
use App\Modules\Player\Application\Handler\InactivatePlayerHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class InactivatePlayerHandlerTest extends TestCase
{
    public function testItInactivatesThePlayerWithinTheAcademy(): void
    {
        $repository = $this->createRepository();
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');

        $handler = new InactivatePlayerHandler(new PlayerFinder($repository), $repository);

        $handler(new InactivatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
        ));

        self::assertTrue($repository->players[$playerId->value()]->status()->isInactive());
    }

    public function testItActivatesThePlayerWithinTheAcademy(): void
    {
        $repository = $this->createRepository(inactive: true);
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');

        $handler = new ActivatePlayerHandler(new PlayerFinder($repository), $repository);

        $handler(new ActivatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
        ));

        self::assertTrue($repository->players[$playerId->value()]->status()->isActive());
    }

    private function createRepository(bool $inactive = false): InMemoryPlayerRepository
    {
        $repository = new InMemoryPlayerRepository();
        $status = $inactive ? 'inactive' : 'active';

        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');

        $player = Player::create(
            $playerId,
            $academyId,
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );

        if ('inactive' === $status) {
            $player->inactivate('019eec93-9a11-7432-bd04-52306b2b3d8e');
        }

        $repository->save($player);

        return $repository;
    }
}
