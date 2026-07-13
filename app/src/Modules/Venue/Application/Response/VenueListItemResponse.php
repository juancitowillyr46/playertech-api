<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Response;

use App\Modules\Venue\Domain\Venue\Venue;

final readonly class VenueListItemResponse
{
    private function __construct(
        private string $id,
        private string $name,
        private ?string $address,
        private ?string $city,
        private ?string $phone,
        private string $status,
    ) {
    }

    public static function fromVenue(Venue $venue): self
    {
        return new self(
            $venue->id()->value(),
            $venue->name()->value(),
            $venue->address()?->value(),
            $venue->city()?->value(),
            $venue->phone()?->value(),
            $venue->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'phone' => $this->phone,
            'status' => $this->status,
        ];
    }
}
