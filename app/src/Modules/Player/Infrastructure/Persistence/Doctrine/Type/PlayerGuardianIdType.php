<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class PlayerGuardianIdType extends AbstractUuidType
{
    public const NAME = 'player_guardian_id';

    protected function getValueObjectClass(): string
    {
        return PlayerGuardianId::class;
    }
}
