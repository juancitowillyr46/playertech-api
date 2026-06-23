<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Response;

use App\Modules\Venue\Domain\Venue\Venue;

final readonly class VenueListItemResponse
{
    private function __construct(
        private string $id,
        private string $name,
        private ?string $city,
        private string $status,
    ) {
    }

    public static function fromVenue(Venue $venue): self
    {
        return new self(
            $venue->id()->value(),
            $venue->name()->value(),
            $venue->city()?->value(),
            $venue->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'status' => $this->status,
        ];
    }
}