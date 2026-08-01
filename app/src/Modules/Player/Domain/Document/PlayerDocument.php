<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Document;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\ValueObject\AuditTrail;

final class PlayerDocument implements Auditable
{
    private function __construct(
        private PlayerDocumentId $id,
        private AcademyId $academyId,
        private PlayerId $playerId,
        private DocumentType $documentType,
        private string $originalFileName,
        private string $storageName,
        private string $mimeType,
        private int $fileSize,
        private string $fileExtension,
        private ?string $observations,
        private AuditTrail $auditTrail,
        private ?\DateTimeImmutable $deletedAt = null,
        private ?string $deletedBy = null,
    ) {}

    public static function create(PlayerDocumentId $id, AcademyId $academyId, PlayerId $playerId, DocumentType $type, array $file, ?string $observations, string $actorId): self
    {
        return new self($id, $academyId, $playerId, $type, $file['originalFileName'], $file['storageName'], $file['mimeType'], $file['size'], $file['extension'], self::text($observations), AuditTrail::create($actorId));
    }

    public function replace(DocumentType $type, array $file, ?string $observations, string $actorId): string
    {
        $oldStorageName = $this->storageName;
        $this->documentType = $type;
        $this->originalFileName = $file['originalFileName'];
        $this->storageName = $file['storageName'];
        $this->mimeType = $file['mimeType'];
        $this->fileSize = $file['size'];
        $this->fileExtension = $file['extension'];
        $this->observations = self::text($observations);
        $this->auditTrail->touch($actorId);
        return $oldStorageName;
    }

    public function delete(string $actorId): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->deletedBy = $actorId;
        $this->auditTrail->touch($actorId);
    }
    public function id(): PlayerDocumentId
    {
        return $this->id;
    }
    public function academyId(): AcademyId
    {
        return $this->academyId;
    }
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }
    public function documentType(): DocumentType
    {
        return $this->documentType;
    }
    public function originalFileName(): string
    {
        return $this->originalFileName;
    }
    public function storageName(): string
    {
        return $this->storageName;
    }
    public function mimeType(): string
    {
        return $this->mimeType;
    }
    public function fileSize(): int
    {
        return $this->fileSize;
    }
    public function fileExtension(): string
    {
        return $this->fileExtension;
    }
    public function observations(): ?string
    {
        return $this->observations;
    }
    public function auditTrail(): AuditTrail
    {
        return $this->auditTrail;
    }
    public function setAuditTrail(AuditTrail $auditTrail): void
    {
        $this->auditTrail = $auditTrail;
    }
    public function deletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }
    public function deletedBy(): ?string
    {
        return $this->deletedBy;
    }
    private static function text(?string $value): ?string
    {
        $value = null === $value ? null : trim($value);
        return '' === $value ? null : $value;
    }
}
