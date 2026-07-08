<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Domain\Exception\PaymentConceptNotFoundException;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;

final readonly class PaymentConceptFinder
{
    public function __construct(private PaymentConceptRepository $repository) {}
    public function findOrFail(AcademyId $academyId, PaymentConceptId $paymentConceptId): PaymentConcept
    {
        $paymentConcept = $this->repository->findById($academyId, $paymentConceptId);
        if (null === $paymentConcept) { throw new PaymentConceptNotFoundException(); }
        return $paymentConcept;
    }
}
