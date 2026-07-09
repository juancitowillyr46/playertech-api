<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\Payment;
use App\Modules\Academy\Domain\Academy\AcademyId;
interface PaymentRepository
{
    public function save(Payment $payment): void;
    public function findById(AcademyId $academyId, PaymentId $paymentId): ?Payment;
    /** @return Payment[] */
    public function findAllByAcademy(AcademyId $academyId): array;
}
