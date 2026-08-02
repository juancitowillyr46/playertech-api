<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Handler\ShowPlayerSummaryHandler;
use App\Modules\Player\Application\Query\ShowPlayerSummaryQuery;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class ShowPlayerSummaryHandlerTest extends TestCase
{
    public function testItShowsACompactPlayerSummary(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $repository = new InMemoryPlayerRepository();
        $repository->save(Player::create(
            $playerId,
            $academyId,
            'CC',
            'Juan',
            'Rodas',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            null,
            'Masculino',
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ShowPlayerSummaryHandler(new PlayerFinder($repository));
        $response = $handler(new ShowPlayerSummaryQuery($academyId, $playerId));

        self::assertSame('Juan', $response->toArray()['firstName']);
        self::assertSame('Rodas', $response->toArray()['lastName']);
        self::assertSame('Masculino', $response->toArray()['gender']);
        self::assertNull($response->toArray()['photo']);
    }
}
