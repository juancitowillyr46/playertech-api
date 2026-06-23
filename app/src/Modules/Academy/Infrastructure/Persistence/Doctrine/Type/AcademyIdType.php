<?php

declare(strict_types=1);

namespace App\Modules\Academy\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class AcademyIdType extends AbstractUuidType
{
    public const NAME = 'academy_id';

    protected function getValueObjectClass(): string
    {
        return \App\Modules\Academy\Domain\Academy\AcademyId::class;
    }
}
