<?php

declare(strict_types=1);

namespace App\Modules\Venue\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Academy\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
use App\Modules\Venue\Domain\Venue\VenueId;

final class VenueIdType extends AbstractUuidType
{
    public const NAME = 'venue_id';

    protected function getValueObjectClass(): string
    {
        return VenueId::class;
    }
}