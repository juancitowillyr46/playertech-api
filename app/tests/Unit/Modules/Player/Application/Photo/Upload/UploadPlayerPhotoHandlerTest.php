<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Photo\Upload;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoCommand;
use App\Modules\Player\Application\Photo\Upload\UploadPlayerPhotoHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Response\MediaResponse;
use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Media;
use App\Tests\Unit\Modules\Player\Application\Handler\InMemoryPlayerRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadPlayerPhotoHandlerTest extends TestCase
{
    public function testItUploadsTheFirstPhotoWithoutTryingToDeleteAnUninitializedMedia(): void
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
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $fileStorage = $this->createMock(FileStorage::class);
        $fileStorage->expects(self::never())
            ->method('delete');
        $fileStorage->expects(self::once())
            ->method('upload')
            ->with(
                self::isInstanceOf(UploadedFile::class),
                'images/players/' . $academyId->value() . '/' . $playerId->value()
            )
            ->willReturn(new Media(
                'images/players/' . $academyId->value() . '/' . $playerId->value() . '/photo.jpg',
                'https://cdn.example.test/images/players/' . $academyId->value() . '/' . $playerId->value() . '/photo.jpg',
                'image/jpeg',
                12345,
                'sha256:' . str_repeat('a', 64),
            ));

        $handler = new UploadPlayerPhotoHandler(
            new PlayerFinder($repository),
            $repository,
            $fileStorage,
        );

        $file = $this->createUploadedFile('photo.jpg', 'image/jpeg');

        $response = $handler(new UploadPlayerPhotoCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            $file,
        ));

        self::assertSame(
            'images/players/' . $academyId->value() . '/' . $playerId->value() . '/photo.jpg',
            $response->toArray()['photo']['path']
        );
        self::assertSame(
            'https://cdn.example.test/images/players/' . $academyId->value() . '/' . $playerId->value() . '/photo.jpg',
            $response->toArray()['photo']['url']
        );
        self::assertSame('image/jpeg', $response->toArray()['photo']['mimeType']);
        self::assertSame(12345, $response->toArray()['photo']['size']);
    }

    private function createUploadedFile(string $originalName, string $mimeType): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'player-photo-');

        if (false === $path) {
            self::fail('No se pudo crear el archivo temporal de prueba.');
        }

        file_put_contents($path, base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxISEhUREhIVFRUVFRUVFRUVFRUVFRUVFRUXFhUVFRUYHSggGBolHRUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OFxAQFysdHR0rKy0rLSstKystKystLS0tKystKystKystKystKystKystKystKystKystKystKystK//AABEIAAEAAQMBIgACEQEDEQH/xAAXAAEBAQEAAAAAAAAAAAAAAAACAwQF/8QAFhABAQEAAAAAAAAAAAAAAAAAAAEC/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAP/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwD7AAB//9k='));

        return new UploadedFile(
            $path,
            $originalName,
            $mimeType,
            null,
            true
        );
    }
}
