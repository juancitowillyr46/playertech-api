<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Charge;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class Charge implements Auditable
{
    private ChargeId $id;
    private AcademyId $academyId;
    private MembershipId $membershipId;
    private PaymentConceptId $paymentConceptId;
    private string $description;
    private float $amount;
    private string $status;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;
    private function __construct(ChargeId $id, AcademyId $academyId, MembershipId $membershipId, PaymentConceptId $paymentConceptId, string $description, float $amount, AuditTrail $auditTrail)
    {
        $this->id = $id; $this->academyId = $academyId; $this->membershipId = $membershipId; $this->paymentConceptId = $paymentConceptId; $this->description = trim($description); $this->amount = $amount; $this->status = ChargeStatus::pending()->value(); $this->auditTrail = $auditTrail;
    }
    public static function create(ChargeId $id, AcademyId $academyId, MembershipId $membershipId, PaymentConceptId $paymentConceptId, string $description, float $amount, AuditTrail $auditTrail): self
    {
        if ('' === trim($description)) { throw new \InvalidArgumentException('Charge description cannot be empty.'); }
        if ($amount <= 0) { throw new \InvalidArgumentException('Charge amount must be greater than zero.'); }
        return new self($id, $academyId, $membershipId, $paymentConceptId, $description, $amount, $auditTrail);
    }
    public function id(): ChargeId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function membershipId(): MembershipId { return $this->membershipId; }
    public function paymentConceptId(): PaymentConceptId { return $this->paymentConceptId; }
    public function description(): string { return $this->description; }
    public function amount(): float { return $this->amount; }
    public function status(): ChargeStatus { return 'PAID' === $this->status ? ChargeStatus::paid() : ChargeStatus::pending(); }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
    public function markPaid(string $updatedBy): void { $this->status = ChargeStatus::paid()->value(); $this->auditTrail?->touch($updatedBy); }
}
