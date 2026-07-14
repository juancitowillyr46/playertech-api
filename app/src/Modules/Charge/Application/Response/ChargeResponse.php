<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Response;
use App\Modules\Charge\Domain\Charge\Charge;
final readonly class ChargeResponse
{
    public function __construct(
        public string $id,
        public string $academyId,
        public string $playerId,
        public string $membershipId,
        public string $paymentConceptId,
        public string $description,
        public string $amount,
        public string $allocatedAmount,
        public string $dueDate,
        public string $source,
        public string $status,
        public string $pendingBalance,
    ) {
    }

    public static function fromCharge(Charge $charge): self
    {
        return new self(
            $charge->id()->value(),
            $charge->academyId()->value(),
            $charge->playerId()->value(),
            $charge->membershipId()->value(),
            $charge->paymentConceptId()->value(),
            $charge->description(),
            number_format((float) $charge->amount(), 2, '.', ''),
            number_format((float) $charge->allocatedAmount(), 2, '.', ''),
            $charge->dueDate()->format('Y-m-d'),
            $charge->source(),
            $charge->status()->value(),
            number_format((float) $charge->pendingBalance(), 2, '.', ''),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'playerId' => $this->playerId,
            'membershipId' => $this->membershipId,
            'paymentConceptId' => $this->paymentConceptId,
            'description' => $this->description,
            'amount' => $this->amount,
            'allocatedAmount' => $this->allocatedAmount,
            'dueDate' => $this->dueDate,
            'source' => $this->source,
            'status' => $this->status,
            'pendingBalance' => $this->pendingBalance,
        ];
    }
}
