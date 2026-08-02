<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Player\ListByGuardian;

final readonly class GuardianPlayerListItemResponse
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private ?string $categoryName,
        private string $relationshipName,
        private bool $principal,
    ) {
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'categoryName' => $this->categoryName,
            'relationshipName' => $this->relationshipName,
            'principal' => $this->principal,
        ];
    }
}
