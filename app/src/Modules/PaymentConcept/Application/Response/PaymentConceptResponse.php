<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Response;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
final readonly class PaymentConceptResponse
{
    public function __construct(public string $id, public string $academyId, public string $code, public string $name, public ?string $description, public string $status) {}
    public static function fromPaymentConcept(PaymentConcept $paymentConcept): self { return new self($paymentConcept->id()->value(), $paymentConcept->academyId()->value(), $paymentConcept->code(), $paymentConcept->name(), $paymentConcept->description(), $paymentConcept->status()->value());}
    public function toArray(): array { return ['id'=>$this->id,'academy_id'=>$this->academyId,'code'=>$this->code,'name'=>$this->name,'description'=>$this->description,'status'=>$this->status];}
}
