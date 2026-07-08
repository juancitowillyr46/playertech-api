<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Exception;

final class MembershipAlreadyExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('The player already has an active membership.');
    }
}
