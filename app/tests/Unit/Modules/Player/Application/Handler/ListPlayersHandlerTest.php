<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Handler\ListPlayersHandler;
use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class ListPlayersHandlerTest extends TestCase
{
    public function testItListsPlayersForTheGivenAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $repository = new InMemoryPlayerRepository();
        $repository->save(Player::create(
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ListPlayersHandler($repository);

        $players = $handler(new ListPlayersQuery($academyId));

        self::assertCount(1, $players);
        self::assertSame('Juan', $players[0]->toArray()['first_name']);
        self::assertSame('12345678', $players[0]->toArray()['document_number']);
    }
}
