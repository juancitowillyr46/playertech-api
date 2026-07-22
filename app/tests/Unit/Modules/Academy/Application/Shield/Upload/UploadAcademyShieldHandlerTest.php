<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Academy\Application\Shield\Upload;

use App\Modules\Academy\Application\Shield\Upload\UploadAcademyShieldCommand;
use App\Modules\Academy\Application\Shield\Upload\UploadAcademyShieldHandler;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\Exception\InvalidMimeTypeException;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadAcademyShieldHandlerTest extends TestCase
{
    public function testItRejectsInvalidMimeTypeBeforeUploading(): void
    {
        $academy = $this->createAcademy();
        $repository = $this->createMock(AcademyRepository::class);
        $fileStorage = $this->createMock(FileStorage::class);
        $handler = new UploadAcademyShieldHandler($repository, $fileStorage);
        $file = $this->createUploadedFile('shield.txt', 'text/plain');

        $repository->expects(self::once())
            ->method('findById')
            ->willReturn($academy);
        $repository->expects(self::never())->method('save');
        $fileStorage->expects(self::never())->method('upload');
        $fileStorage->expects(self::never())->method('delete');

        $this->expectException(InvalidMimeTypeException::class);

        $handler(new UploadAcademyShieldCommand(
            '019f0000-0000-7000-8000-000000000000',
            $academy->id()->value(),
            $file
        ));
    }

    private function createAcademy(): Academy
    {
        return Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 123 456'),
            'Colombia',
            'Cundinamarca',
            'NIT',
            '901234567-8',
            'RESPONSABLE_IVA',
            'facturacion@test.local',
            'signup',
            new Address('Av. Principal 123'),
            new City('Bogota'),
            null,
            AuditTrail::create('system'),
            '8',
        );
    }

    private function createUploadedFile(string $name, string $mimeType): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'academy-shield-test-');
        if (false === $path) {
            self::fail('No se pudo crear un archivo temporal para la prueba.');
        }

        file_put_contents($path, 'not-an-image');

        return new UploadedFile(
            $path,
            $name,
            $mimeType,
            null,
            true
        );
    }
}
