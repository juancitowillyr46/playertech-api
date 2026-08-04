<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Response;

use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\Relationship\Relationship;

final readonly class GuardianOptionResponse
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private ?string $documentNumber,
        private ?string $documentTypeName,
        private ?string $relationshipName,
    ) {
    }

    public static function fromGuardian(LegalGuardian $guardian): self
    {
        return new self(
            $guardian->id()->value(),
            $guardian->firstName(),
            $guardian->lastName(),
            $guardian->documentNumber(),
            self::documentTypeNameFrom($guardian->documentType()),
            self::relationshipNameFrom($guardian->relationship()),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'documentNumber' => $this->documentNumber,
            'documentTypeName' => $this->documentTypeName,
            'relationshipName' => $this->relationshipName,
        ];
    }

    private static function documentTypeNameFrom(?string $documentType): ?string
    {
        if (null === $documentType || '' === trim($documentType)) {
            return null;
        }

        $normalized = strtoupper(trim($documentType));

        return DocumentType::tryFrom($normalized)?->label() ?? $documentType;
    }

    private static function relationshipNameFrom(string $relationship): ?string
    {
        $normalized = strtoupper(trim($relationship));

        return Relationship::tryFrom($normalized)?->label() ?? $relationship;
    }
}
