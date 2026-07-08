<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Response;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
final readonly class PaymentConceptListItemResponse
{
    public function __construct(public string $id, public string $code, public string $name, public string $status) {}
    public static function fromPaymentConcept(PaymentConcept $paymentConcept): self { return new self($paymentConcept->id()->value(), $paymentConcept->code(), $paymentConcept->name(), $paymentConcept->status()->value());}
    public function toArray(): array { return ['id'=>$this->id,'code'=>$this->code,'name'=>$this->name,'status'=>$this->status];}
}
