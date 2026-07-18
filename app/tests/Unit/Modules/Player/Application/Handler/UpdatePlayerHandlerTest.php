<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Application\Dto\UpdatePlayerInput;
use App\Modules\Player\Application\Handler\UpdatePlayerHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class UpdatePlayerHandlerTest extends TestCase
{
    public function testItUpdatesThePlayerWithinTheAcademy(): void
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

        $handler = new UpdatePlayerHandler(new PlayerFinder($repository), $repository);

        $response = $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            UpdatePlayerInput::fromArray([
                'documentType' => 'DNI',
                'firstName' => 'Juan Carlos',
                'lastName' => 'Pérez Gómez',
                'birthDate' => '2014-05-20',
                'documentNumber' => '87654321',
            ]),
        ));

        self::assertSame('Juan Carlos', $response->toArray()['firstName']);
        self::assertSame('Pérez Gómez', $response->toArray()['lastName']);
        self::assertSame('2014-05-20', $response->toArray()['birthDate']);
        self::assertSame('87654321', $response->toArray()['documentNumber']);
    }

    public function testItRejectsDuplicateDocumentNumberWithinTheSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $otherPlayerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d91');
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
        $repository->save(Player::create(
            $otherPlayerId,
            $academyId,
            'DNI',
            'Pedro',
            'López',
            new \DateTimeImmutable('2014-06-18'),
            '87654321',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(new PlayerFinder($repository), $repository);

        $this->expectException(PlayerAlreadyExistsException::class);

        $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            UpdatePlayerInput::fromArray([
                'documentType' => 'DNI',
                'firstName' => 'Juan Carlos',
                'lastName' => 'Pérez Gómez',
                'birthDate' => '2014-05-20',
                'documentNumber' => '87654321',
            ]),
        ));
    }
}
