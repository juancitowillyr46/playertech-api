<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class LegalGuardianIdType extends AbstractUuidType
{
    public const NAME = 'legal_guardian_id';

    protected function getValueObjectClass(): string
    {
        return LegalGuardianId::class;
    }
}
