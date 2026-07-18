<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\CreatePlayerCommand;
use App\Modules\Player\Application\Dto\CreatePlayerInput;
use App\Modules\Player\Application\Handler\CreatePlayerHandler;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;
use PHPUnit\Framework\TestCase;

final class CreatePlayerHandlerTest extends TestCase
{
    public function testItCreatesPlayerWithinAcademyContext(): void
    {
        $repository = new InMemoryPlayerRepository();
        $handler = new CreatePlayerHandler($repository);

        $response = $handler(new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678'),
        ));

        self::assertSame('DNI', $response->toArray()['documentType']);
        self::assertSame('Juan', $response->toArray()['firstName']);
        self::assertSame('Pérez', $response->toArray()['lastName']);
        self::assertSame('2014-05-18', $response->toArray()['birthDate']);
        self::assertSame('12345678', $response->toArray()['documentNumber']);
        self::assertSame('ACTIVE', $response->toArray()['status']);
        self::assertCount(1, $repository->players);
    }

    public function testItRejectsDuplicateDocumentWithinTheSameAcademy(): void
    {
        $repository = new InMemoryPlayerRepository();
        $handler = new CreatePlayerHandler($repository);

        $command = new CreatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            '019eec93-9a11-7432-bd04-52306b2b3d8f',
            new CreatePlayerInput('DNI', 'Juan', 'Pérez', '2014-05-18', '12345678'),
        );

        $handler($command);

        $this->expectException(PlayerAlreadyExistsException::class);

        $handler($command);
    }
}
