<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Charge;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class Charge implements Auditable
{
    private ChargeId $id;
    private AcademyId $academyId;
    private PlayerId $playerId;
    private MembershipId $membershipId;
    private PaymentConceptId $paymentConceptId;
    private string $description;
    private float $amount;
    private float $allocatedAmount = 0.0;
    private \DateTimeImmutable $dueDate;
    private string $source;
    private string $status;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;
    private function __construct(ChargeId $id, AcademyId $academyId, PlayerId $playerId, MembershipId $membershipId, PaymentConceptId $paymentConceptId, string $description, float $amount, \DateTimeImmutable $dueDate, string $source, AuditTrail $auditTrail)
    {
        $this->id = $id; $this->academyId = $academyId; $this->playerId = $playerId; $this->membershipId = $membershipId; $this->paymentConceptId = $paymentConceptId; $this->description = trim($description); $this->amount = $amount; $this->dueDate = $dueDate; $this->source = strtoupper(trim($source)); $this->status = ChargeStatus::pending()->value(); $this->auditTrail = $auditTrail;
    }
    public static function create(ChargeId $id, AcademyId $academyId, PlayerId $playerId, MembershipId $membershipId, PaymentConceptId $paymentConceptId, string $description, float $amount, \DateTimeImmutable $dueDate, string $source, AuditTrail $auditTrail): self
    {
        if ('' === trim($description)) { throw new \InvalidArgumentException('Charge description cannot be empty.'); }
        if ($amount <= 0) { throw new \InvalidArgumentException('Charge amount must be greater than zero.'); }
        if (!in_array(strtoupper(trim($source)), ['MANUAL', 'AUTOMATIC'], true)) { throw new \InvalidArgumentException('Charge source is invalid.'); }
        return new self($id, $academyId, $playerId, $membershipId, $paymentConceptId, $description, $amount, $dueDate, $source, $auditTrail);
    }
    public function id(): ChargeId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function playerId(): PlayerId { return $this->playerId; }
    public function membershipId(): MembershipId { return $this->membershipId; }
    public function paymentConceptId(): PaymentConceptId { return $this->paymentConceptId; }
    public function description(): string { return $this->description; }
    public function amount(): float { return $this->amount; }
    public function allocatedAmount(): float { return $this->allocatedAmount; }
    public function dueDate(): \DateTimeImmutable { return $this->dueDate; }
    public function source(): string { return $this->source; }
    public function status(): ChargeStatus
    {
        return match ($this->status) {
            'PAID' => ChargeStatus::paid(),
            'PARTIAL' => ChargeStatus::partial(),
            default => ChargeStatus::pending(),
        };
    }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
    public function applyAllocation(float $amount, string $updatedBy): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Allocated amount must be greater than zero.');
        }

        $nextAllocated = $this->allocatedAmount + $amount;
        if ($nextAllocated > $this->amount + 0.00001) {
            throw new \InvalidArgumentException('Allocated amount exceeds charge balance.');
        }

        $this->allocatedAmount = round($nextAllocated, 2);
        if ($this->allocatedAmount >= $this->amount) {
            $this->status = ChargeStatus::paid()->value();
        } elseif ($this->allocatedAmount > 0.0) {
            $this->status = ChargeStatus::partial()->value();
        }

        $this->auditTrail?->touch($updatedBy);
    }

    public function markPaid(string $updatedBy): void
    {
        $this->allocatedAmount = $this->amount;
        $this->status = ChargeStatus::paid()->value();
        $this->auditTrail?->touch($updatedBy);
    }

    public function pendingBalance(): float
    {
        return max(0.0, round($this->amount - $this->allocatedAmount, 2));
    }
}
