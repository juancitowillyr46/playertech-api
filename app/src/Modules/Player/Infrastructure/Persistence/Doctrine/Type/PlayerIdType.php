<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class PlayerIdType extends AbstractUuidType
{
    public const NAME = 'player_id';

    protected function getValueObjectClass(): string
    {
        return PlayerId::class;
    }
}
