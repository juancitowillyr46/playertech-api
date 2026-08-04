<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Response;

use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\Relationship\Relationship;

final readonly class LegalGuardianResponse
{
    public function __construct(
        private string $id,
        private string $academyId,
        private string $firstName,
        private string $lastName,
        private ?string $phone,
        private ?string $email,
        private ?string $documentType,
        private ?string $documentNumber,
        private ?string $address,
        private string $relationship,
        private string $status,
    ) {
    }

    public static function fromLegalGuardian(LegalGuardian $guardian): self
    {
        return new self(
            $guardian->id()->value(),
            $guardian->academyId()->value(),
            $guardian->firstName(),
            $guardian->lastName(),
            $guardian->phone(),
            $guardian->email(),
            $guardian->documentType(),
            $guardian->documentNumber(),
            $guardian->address(),
            $guardian->relationship(),
            $guardian->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email,
            'documentType' => $this->documentType,
            'documentTypeName' => self::documentTypeNameFrom($this->documentType),
            'documentNumber' => $this->documentNumber,
            'address' => $this->address,
            'relationship' => $this->relationship,
            'relationshipName' => self::relationshipNameFrom($this->relationship),
            'status' => $this->status,
        ];
    }

    private static function documentTypeNameFrom(?string $documentType): ?string
    {
        if (null === $documentType) {
            return null;
        }

        $normalized = strtoupper(trim($documentType));

        return DocumentType::tryFrom($normalized)?->label()
            ?? self::matchLabelOrValue($normalized, DocumentType::cases())
            ?? $documentType;
    }

    private static function relationshipNameFrom(string $relationship): string
    {
        $normalized = strtoupper(trim($relationship));

        return Relationship::tryFrom($normalized)?->label()
            ?? self::matchLabelOrValue($normalized, Relationship::cases())
            ?? $relationship;
    }

    /**
     * @param array<int, \UnitEnum> $cases
     */
    private static function matchLabelOrValue(string $value, array $cases): ?string
    {
        foreach ($cases as $case) {
            if ($case instanceof DocumentType || $case instanceof Relationship) {
                if (strtoupper(trim($case->value)) === $value || strtoupper(trim($case->label())) === $value) {
                    return $case->label();
                }
            }
        }

        return null;
    }
}
