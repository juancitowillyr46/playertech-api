<?php
declare(strict_types=1);
namespace App\Modules\Charge\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class ChargeIdType extends AbstractUuidType
{
    public const NAME = 'charge_id';
    protected function getValueObjectClass(): string { return ChargeId::class; }
    public function getName(): string { return self::NAME; }
}
