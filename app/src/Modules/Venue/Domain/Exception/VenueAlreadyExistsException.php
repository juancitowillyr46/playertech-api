<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Exception;

final class VenueAlreadyExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'A venue with the same name already exists for this academy.'
        );
    }
}