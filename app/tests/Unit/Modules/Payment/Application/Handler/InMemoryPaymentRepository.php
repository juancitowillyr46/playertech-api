<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
final class InMemoryPaymentRepository implements PaymentRepository
{
    /** @var array<string, Payment> */
    public array $items = [];
    public function save(Payment $payment): void { $this->items[$payment->id()->value()] = $payment; }
    public function findById(AcademyId $academyId, PaymentId $paymentId): ?Payment { return $this->items[$paymentId->value()] ?? null; }
    public function findAllByAcademy(AcademyId $academyId): array { return array_values(array_filter($this->items, static fn (Payment $payment): bool => $payment->academyId()->equals($academyId))); }
}
