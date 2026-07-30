<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Response;
use App\Modules\Player\Domain\Document\PlayerDocument;
readonly class PlayerDocumentItemResponse
{
    protected function __construct(private PlayerDocument $document) {}
    public static function fromDocument(PlayerDocument $document): self { return new self($document); }
    public function toArray(): array { $d = $this->document; $updatedAt = $d->auditTrail()->updatedAt()?->value(); return ['id' => $d->id()->value(), 'playerId' => $d->playerId()->value(), 'documentType' => $d->documentType()->value, 'originalFileName' => $d->originalFileName(), 'mimeType' => $d->mimeType(), 'fileSize' => $d->fileSize(), 'fileExtension' => $d->fileExtension(), 'observations' => $d->observations(), 'createdAt' => $d->auditTrail()->createdAt()->value()->format(DATE_ATOM), 'updatedAt' => $updatedAt?->format(DATE_ATOM)]; }
}
