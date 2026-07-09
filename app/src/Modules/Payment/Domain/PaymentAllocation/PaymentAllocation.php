<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\PaymentAllocation;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class PaymentAllocation implements Auditable
{
    private PaymentAllocationId $id;
    private AcademyId $academyId;
    private PaymentId $paymentId;
    private ChargeId $chargeId;
    private float $amount;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;
    private function __construct(PaymentAllocationId $id, AcademyId $academyId, PaymentId $paymentId, ChargeId $chargeId, float $amount, AuditTrail $auditTrail)
    {
        $this->id = $id; $this->academyId = $academyId; $this->paymentId = $paymentId; $this->chargeId = $chargeId; $this->amount = $amount; $this->auditTrail = $auditTrail;
    }
    public static function create(PaymentAllocationId $id, AcademyId $academyId, PaymentId $paymentId, ChargeId $chargeId, float $amount, AuditTrail $auditTrail): self
    {
        if ($amount <= 0) { throw new \InvalidArgumentException('Allocation amount must be greater than zero.'); }
        return new self($id, $academyId, $paymentId, $chargeId, $amount, $auditTrail);
    }
    public function id(): PaymentAllocationId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function paymentId(): PaymentId { return $this->paymentId; }
    public function chargeId(): ChargeId { return $this->chargeId; }
    public function amount(): float { return $this->amount; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
}
