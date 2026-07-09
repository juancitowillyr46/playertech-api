<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Domain\PaymentConcept;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Application\Pagination\PaginationQuery;

interface PaymentConceptRepository
{
    public function save(PaymentConcept $paymentConcept): void;
    public function findById(AcademyId $academyId, PaymentConceptId $paymentConceptId): ?PaymentConcept;
    public function findByCode(AcademyId $academyId, string $code): ?PaymentConcept;
    /** @return array{items: PaymentConcept[], total: int} */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;
}
