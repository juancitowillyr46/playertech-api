<?php
declare(strict_types=1);
namespace App\Modules\Payment\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Payment\Domain\PaymentEvidence\PaymentEvidenceId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class PaymentEvidenceIdType extends AbstractUuidType
{
    public const NAME='payment_evidence_id';
    protected function getValueObjectClass(): string { return PaymentEvidenceId::class; }
    public function getName(): string { return self::NAME; }
}
