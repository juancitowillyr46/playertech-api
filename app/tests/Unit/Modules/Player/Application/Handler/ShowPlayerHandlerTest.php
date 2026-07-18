<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Handler\ShowPlayerHandler;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class ShowPlayerHandlerTest extends TestCase
{
    public function testItShowsThePlayerDetailWithinTheAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $repository = new InMemoryPlayerRepository();
        $repository->save(Player::create(
            $playerId,
            $academyId,
            'DNI',
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
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ShowPlayerHandler(new PlayerFinder($repository));

        $response = $handler(new ShowPlayerQuery($academyId, $playerId));

        self::assertSame('Juan', $response->toArray()['firstName']);
        self::assertSame('Pérez', $response->toArray()['lastName']);
        self::assertSame('12345678', $response->toArray()['documentNumber']);
    }

    public function testItRejectsMissingPlayer(): void
    {
        $this->expectException(PlayerNotFoundException::class);

        $handler = new ShowPlayerHandler(new PlayerFinder(new InMemoryPlayerRepository()));

        $handler(new ShowPlayerQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));
    }
}
