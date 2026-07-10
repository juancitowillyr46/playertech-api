<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Response;
use App\Modules\Payment\Domain\Payment\Payment;
final readonly class PaymentResponse
{
    public function __construct(
        public string $id,
        public string $membershipId,
        public string $playerId,
        public string $guardianId,
        public string $paymentConceptId,
        public string $paymentDate,
        public string $amount,
        public string $status,
    ) {
    }

    public static function fromPayment(Payment $payment): self
    {
        return new self(
            $payment->id()->value(),
            $payment->membershipId()->value(),
            $payment->playerId()->value(),
            $payment->guardianId()->value(),
            $payment->paymentConceptId()->value(),
            $payment->paymentDate()->format('Y-m-d'),
            number_format((float) $payment->amount(), 2, '.', ''),
            $payment->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'membershipId' => $this->membershipId,
            'playerId' => $this->playerId,
            'guardianId' => $this->guardianId,
            'paymentConceptId' => $this->paymentConceptId,
            'paymentDate' => $this->paymentDate,
            'amount' => $this->amount,
            'status' => $this->status,
        ];
    }
}
