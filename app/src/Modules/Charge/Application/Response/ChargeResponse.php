<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Response;
use App\Modules\Charge\Domain\Charge\Charge;
final readonly class ChargeResponse
{
    public function __construct(public string $id, public string $membershipId, public string $paymentConceptId, public string $description, public string $amount, public string $status) {}
    public static function fromCharge(Charge $charge): self { return new self($charge->id()->value(), $charge->membershipId()->value(), $charge->paymentConceptId()->value(), $charge->description(), number_format((float) $charge->amount(), 2, '.', ''), $charge->status()->value()); }
    public function toArray(): array { return ['id'=>$this->id,'membershipId'=>$this->membershipId,'paymentConceptId'=>$this->paymentConceptId,'description'=>$this->description,'amount'=>$this->amount,'status'=>$this->status]; }
}
