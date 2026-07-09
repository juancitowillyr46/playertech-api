<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Response;
use App\Modules\Payment\Domain\Payment\Payment;
final readonly class PaymentResponse
{
    public function __construct(public string $id, public string $membershipId, public string $playerId, public string $guardianId, public string $paymentConceptId, public string $paymentDate, public string $amount, public string $status) {}
    public static function fromPayment(Payment $payment): self
    {
        return new self($payment->id()->value(), $payment->membershipId()->value(), $payment->playerId()->value(), $payment->guardianId()->value(), $payment->paymentConceptId()->value(), $payment->paymentDate()->format('Y-m-d'), number_format((float) $payment->amount(), 2, '.', ''), $payment->status()->value());
    }
    public function toArray(): array
    {
        return ['id'=>$this->id,'membership_id'=>$this->membershipId,'player_id'=>$this->playerId,'guardian_id'=>$this->guardianId,'payment_concept_id'=>$this->paymentConceptId,'payment_date'=>$this->paymentDate,'amount'=>$this->amount,'status'=>$this->status];
    }
}
