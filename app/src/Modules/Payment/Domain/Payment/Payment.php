<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\Payment;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class Payment implements Auditable
{
    private PaymentId $id;
    private AcademyId $academyId;
    private MembershipId $membershipId;
    private PlayerId $playerId;
    private LegalGuardianId $guardianId;
    private PaymentConceptId $paymentConceptId;
    private \DateTimeImmutable $paymentDate;
    private float $amount;
    private ?string $notes;
    private PaymentStatus $status;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;
    private function __construct(PaymentId $id, AcademyId $academyId, MembershipId $membershipId, PlayerId $playerId, LegalGuardianId $guardianId, PaymentConceptId $paymentConceptId, \DateTimeImmutable $paymentDate, float $amount, ?string $notes, AuditTrail $auditTrail)
    {
        $this->id = $id; $this->academyId = $academyId; $this->membershipId = $membershipId; $this->playerId = $playerId; $this->guardianId = $guardianId; $this->paymentConceptId = $paymentConceptId; $this->paymentDate = $paymentDate; $this->amount = $amount; $this->notes = $notes; $this->status = PaymentStatus::registered(); $this->auditTrail = $auditTrail;
    }
    public static function create(PaymentId $id, AcademyId $academyId, MembershipId $membershipId, PlayerId $playerId, LegalGuardianId $guardianId, PaymentConceptId $paymentConceptId, \DateTimeImmutable $paymentDate, float $amount, ?string $notes, AuditTrail $auditTrail): self
    {
        if ($amount <= 0) { throw new \InvalidArgumentException('Payment amount must be greater than zero.'); }
        return new self($id, $academyId, $membershipId, $playerId, $guardianId, $paymentConceptId, $paymentDate, $amount, $notes, $auditTrail);
    }
    public function id(): PaymentId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function membershipId(): MembershipId { return $this->membershipId; }
    public function playerId(): PlayerId { return $this->playerId; }
    public function guardianId(): LegalGuardianId { return $this->guardianId; }
    public function paymentConceptId(): PaymentConceptId { return $this->paymentConceptId; }
    public function paymentDate(): \DateTimeImmutable { return $this->paymentDate; }
    public function amount(): float { return $this->amount; }
    public function notes(): ?string { return $this->notes; }
    public function status(): PaymentStatus { return $this->status; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
}
