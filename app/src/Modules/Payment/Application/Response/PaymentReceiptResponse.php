<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Response;

use App\Modules\Payment\Domain\Payment\Payment;

final readonly class PaymentReceiptResponse
{
    private function __construct(
        private string $receiptNumber,
        private string $paymentId,
        private string $academyId,
        private ?string $academyTaxIdType,
        private ?string $academyTaxIdNumber,
        private ?string $academyTaxCheckDigit,
        private ?string $academyTaxRegime,
        private ?string $academyBillingEmail,
        private ?string $academyAddress,
        private ?string $academyCity,
        private string $guardianId,
        private string $playerId,
        private string $paymentDate,
        private string $amount,
        private string $method,
        private string $conceptCode,
        private string $conceptName,
        private array $allocations,
    ) {
    }

    public static function fromPayment(
        Payment $payment,
        string $conceptCode,
        string $conceptName,
        ?string $academyTaxIdType,
        ?string $academyTaxIdNumber,
        ?string $academyTaxCheckDigit,
        ?string $academyTaxRegime,
        ?string $academyBillingEmail,
        ?string $academyAddress,
        ?string $academyCity,
    ): self
    {
        $date = $payment->paymentDate()->format('Ymd');
        $suffix = substr($payment->id()->value(), -6);

        return new self(
            sprintf('RCPT-%s-%s', $date, strtoupper($suffix)),
            $payment->id()->value(),
            $payment->academyId()->value(),
            $academyTaxIdType,
            $academyTaxIdNumber,
            $academyTaxCheckDigit,
            $academyTaxRegime,
            $academyBillingEmail,
            $academyAddress,
            $academyCity,
            $payment->guardianId()->value(),
            $payment->playerId()->value(),
            $payment->paymentDate()->format('Y-m-d'),
            number_format((float) $payment->amount(), 2, '.', ''),
            $payment->method(),
            $conceptCode,
            $conceptName,
            $payment->allocations(),
        );
    }

    public function toArray(): array
    {
        return [
            'receiptNumber' => $this->receiptNumber,
            'paymentId' => $this->paymentId,
            'academyId' => $this->academyId,
            'academyTaxIdType' => $this->academyTaxIdType,
            'academyTaxIdNumber' => $this->academyTaxIdNumber,
            'academyTaxCheckDigit' => $this->academyTaxCheckDigit,
            'academyTaxRegime' => $this->academyTaxRegime,
            'academyBillingEmail' => $this->academyBillingEmail,
            'academyAddress' => $this->academyAddress,
            'academyCity' => $this->academyCity,
            'guardianId' => $this->guardianId,
            'playerId' => $this->playerId,
            'paymentDate' => $this->paymentDate,
            'amount' => $this->amount,
            'method' => $this->method,
            'conceptCode' => $this->conceptCode,
            'conceptName' => $this->conceptName,
            'allocations' => $this->allocations,
        ];
    }
}
