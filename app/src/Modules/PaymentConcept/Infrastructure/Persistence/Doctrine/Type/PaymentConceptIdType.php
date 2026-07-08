<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class PaymentConceptIdType extends AbstractUuidType
{
    public const NAME = 'payment_concept_id';
    protected function getValueObjectClass(): string { return PaymentConceptId::class; }
    public function getName(): string { return self::NAME; }
}
