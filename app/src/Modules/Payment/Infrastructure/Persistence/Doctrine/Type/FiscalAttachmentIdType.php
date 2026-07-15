<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachmentId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class FiscalAttachmentIdType extends AbstractUuidType
{
    protected function getValueObjectClass(): string
    {
        return FiscalAttachmentId::class;
    }
}
