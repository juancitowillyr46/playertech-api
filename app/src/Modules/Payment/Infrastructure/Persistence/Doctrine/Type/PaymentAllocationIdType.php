<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class PaymentAllocationIdType extends AbstractUuidType
{
    public const NAME = 'payment_allocation_id';
    protected function getValueObjectClass(): string { return PaymentAllocationId::class; }
    public function getName(): string { return self::NAME; }
}
