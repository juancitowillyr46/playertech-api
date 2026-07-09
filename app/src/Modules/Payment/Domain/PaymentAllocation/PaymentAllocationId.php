<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\PaymentAllocation;
use Symfony\Component\Uid\Uuid;
final readonly class PaymentAllocationId
{
    public function __construct(private string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid payment allocation id.');
        }
    }
    public static function generate(): self { return new self(Uuid::v7()->toRfc4122()); }
    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }
}
