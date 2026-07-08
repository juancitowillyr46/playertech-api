<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Domain\PaymentConcept;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface PaymentConceptRepository
{
    public function save(PaymentConcept $paymentConcept): void;
    public function findById(AcademyId $academyId, PaymentConceptId $paymentConceptId): ?PaymentConcept;
    public function findByCode(AcademyId $academyId, string $code): ?PaymentConcept;
    /** @return PaymentConcept[] */
    public function findAllByAcademy(AcademyId $academyId): array;
}
