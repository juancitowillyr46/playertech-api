<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Exception;

final class VenueNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'venue not found'
        );
    }
}