<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Response;
use App\Modules\Payment\Domain\Payment\Payment;
final readonly class PaymentHistoryItemResponse
{
    public function __construct(public string $id, public string $paymentDate, public string $amount, public string $status, public string $paymentConceptId) {}
    public static function fromPayment(Payment $payment): self
    {
        return new self($payment->id()->value(), $payment->paymentDate()->format(DATE_ATOM), number_format((float) $payment->amount(), 2, '.', ''), $payment->status()->value(), $payment->paymentConceptId()->value());
    }
    public function toArray(): array
    {
        return ['id'=>$this->id,'payment_date'=>$this->paymentDate,'amount'=>$this->amount,'status'=>$this->status,'payment_concept_id'=>$this->paymentConceptId];
    }
}
