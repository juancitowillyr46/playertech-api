<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Shield\Upload;

use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\Exception\InvalidMimeTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class UploadAcademyShieldHandler
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/svg+xml',
    ];

    public function __construct(
        private AcademyRepository $academyRepository,
        private FileStorage $fileStorage,
    ) {
    }

    public function __invoke(UploadAcademyShieldCommand $command): AcademyResponse
    {
        $academy = $this->requireAcademy($command->academyId);
        $this->assertAllowedMimeType($command->file);

        $shield = $academy->shield();
        if (null !== $shield && $this->isInitializedMedia($shield)) {
            $this->fileStorage->delete($shield);
        }

        $media = $this->fileStorage->upload(
            $command->file,
            'images/academies/' . $academy->id()->value()
        );

        $academy->updateShield($media, $command->actorId);

        $this->academyRepository->save($academy);

        return AcademyResponse::fromAcademy($academy);
    }

    private function assertAllowedMimeType(UploadedFile $file): void
    {
        $mimeType = $file->getMimeType() ?? $file->getClientMimeType() ?? '';

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new InvalidMimeTypeException($mimeType, self::ALLOWED_MIME_TYPES);
        }
    }

    private function requireAcademy(string $academyId): \App\Modules\Academy\Domain\Academy\Academy
    {
        if (!Uuid::isValid($academyId)) {
            throw new NotFoundHttpException('Identificador de academia inválido.');
        }

        $academy = $this->academyRepository->findById(new AcademyId($academyId));

        if (null === $academy) {
            throw new NotFoundHttpException('Academia no encontrada.');
        }

        return $academy;
    }

    private function isInitializedMedia(\App\Shared\Domain\ValueObject\Media $media): bool
    {
        $reflection = new \ReflectionObject($media);

        foreach (['path', 'url', 'mimeType', 'size', 'checksum'] as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if (!$property->isInitialized($media)) {
                return false;
            }
        }

        return true;
    }
}
