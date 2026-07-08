<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class MembershipAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('The player already has an active membership.');
    }
}
