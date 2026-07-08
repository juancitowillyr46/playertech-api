<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Exception;

final class MembershipNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Membership not found.');
    }
}
