<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class MembershipNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('Membership not found.');
    }
}
