<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Photo\Delete;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Photo\Delete\DeletePlayerPhotoCommand;
use App\Modules\Player\Application\Photo\Delete\DeletePlayerPhotoHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Media;
use App\Tests\Unit\Modules\Player\Application\Handler\InMemoryPlayerRepository;
use PHPUnit\Framework\TestCase;

final class DeletePlayerPhotoHandlerTest extends TestCase
{
    public function testItDeletesThePlayerPhotoWhenOneExists(): void
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
            null,
            new Media(
                'images/players/' . $academyId->value() . '/' . $playerId->value() . '/photo.jpg',
                'https://cdn.example.test/photo.jpg',
                'image/jpeg',
                123,
                'sha256:' . str_repeat('a', 64),
            ),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $fileStorage = $this->createMock(FileStorage::class);
        $fileStorage->expects(self::once())->method('delete');

        $handler = new DeletePlayerPhotoHandler(
            new PlayerFinder($repository),
            $repository,
            $fileStorage,
        );

        $handler(new DeletePlayerPhotoCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
        ));

        self::assertNull($repository->players[$playerId->value()]->photo());
    }

    public function testItDoesNothingWhenThePlayerHasNoPhoto(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d91');
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
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $fileStorage = $this->createMock(FileStorage::class);
        $fileStorage->expects(self::never())->method('delete');

        $handler = new DeletePlayerPhotoHandler(
            new PlayerFinder($repository),
            $repository,
            $fileStorage,
        );

        $handler(new DeletePlayerPhotoCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
        ));

        self::assertNull($repository->players[$playerId->value()]->photo());
    }
}
