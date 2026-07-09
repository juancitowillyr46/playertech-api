<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\PaymentAllocation;
use App\Modules\Academy\Domain\Academy\AcademyId;
interface PaymentAllocationRepository
{
    public function save(PaymentAllocation $allocation): void;
    public function findByPaymentAndCharge(AcademyId $academyId, string $paymentId, string $chargeId): ?PaymentAllocation;
}
