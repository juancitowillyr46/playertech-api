<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Response;

final readonly class LegalGuardianResponse
{
    public function __construct(
        private string $id,
        private string $academyId,
        private string $firstName,
        private string $lastName,
        private ?string $phone,
        private ?string $email,
        private string $status,
    ) {
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
            'status' => $this->status,
        ];
    }
}
