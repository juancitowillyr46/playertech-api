<?php

declare(strict_types=1);

namespace App\Modules\Team\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class TeamIdType extends AbstractUuidType
{
    public const NAME = 'team_id';

    protected function getValueObjectClass(): string
    {
        return TeamId::class;
    }
}
