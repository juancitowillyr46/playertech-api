<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class PaymentIdType extends AbstractUuidType
{
    public const NAME = 'payment_id';
    protected function getValueObjectClass(): string { return PaymentId::class; }
    public function getName(): string { return self::NAME; }
}
