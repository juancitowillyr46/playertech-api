<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Player\Domain\Import\PlayerImportJobId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class PlayerImportJobIdType extends AbstractUuidType
{
    public const NAME = 'player_import_job_id';

    protected function getValueObjectClass(): string
    {
        return PlayerImportJobId::class;
    }
}
