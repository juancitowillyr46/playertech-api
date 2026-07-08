<?php

declare(strict_types=1);

namespace App\Modules\Membership\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class MembershipIdType extends AbstractUuidType
{
    public const NAME = 'membership_id';

    protected function getValueObjectClass(): string
    {
        return MembershipId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
