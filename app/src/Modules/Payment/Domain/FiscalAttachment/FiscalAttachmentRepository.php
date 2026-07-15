<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\FiscalAttachment;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface FiscalAttachmentRepository
{
    public function save(FiscalAttachment $attachment): void;

    /** @return FiscalAttachment[] */
    public function findAllByAcademy(AcademyId $academyId): array;
}
