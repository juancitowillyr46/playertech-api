<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Response;

use App\Modules\Venue\Domain\Venue\Venue;

final readonly class VenueResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $name,
        private ?string $address,
        private ?string $city,
        private ?string $country,
        private ?string $department,
        private ?string $phone,
        private ?string $notes,
        private bool $isPrimary,
        private string $status
    ) {
    }

    public static function fromVenue(Venue $venue): self
    {
        return new self(
            $venue->id()->value(),
            $venue->academyId()->value(),
            $venue->name()->value(),
            $venue->address()?->value(),
            $venue->city()?->value(),
            $venue->country(),
            $venue->department(),
            $venue->phone()?->value(),
            $venue->notes()?->value(),
            $venue->isPrimary(),
            $venue->status()->value()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'department' => $this->department,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'isPrimary' => $this->isPrimary,
            'status' => $this->status,
        ];
    }
}
