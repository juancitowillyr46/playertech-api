<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocation;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationRepository;
final class InMemoryPaymentAllocationRepository implements PaymentAllocationRepository
{
    /** @var array<string, PaymentAllocation> */
    public array $items = [];
    public function save(PaymentAllocation $allocation): void { $this->items[$allocation->id()->value()] = $allocation; }
    public function findByPaymentAndCharge(AcademyId $academyId, string $paymentId, string $chargeId): ?PaymentAllocation
    {
        foreach ($this->items as $allocation) {
            if ($allocation->academyId()->equals($academyId) && $allocation->paymentId()->value() === $paymentId && $allocation->chargeId()->value() === $chargeId) {
                return $allocation;
            }
        }
        return null;
    }
}
