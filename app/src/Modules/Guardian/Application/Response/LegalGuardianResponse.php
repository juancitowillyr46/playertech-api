<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Response;

use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;

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
            'documentNumber' => $this->documentNumber,
            'address' => $this->address,
            'relationship' => $this->relationship,
            'status' => $this->status,
        ];
    }
}
