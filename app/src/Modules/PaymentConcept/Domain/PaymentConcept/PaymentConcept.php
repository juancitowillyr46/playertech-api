<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Domain\PaymentConcept;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class PaymentConcept implements Auditable
{
    private PaymentConceptId $id;
    private AcademyId $academyId;
    private string $code;
    private string $name;
    private ?string $description;
    private PaymentConceptStatus $status;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;

    private function __construct(
        PaymentConceptId $id,
        AcademyId $academyId,
        string $code,
        string $name,
        ?string $description,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->code = self::normalizeCode($code);
        $this->name = self::normalizeName($name);
        $this->description = self::normalizeNullableText($description);
        $this->status = PaymentConceptStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        PaymentConceptId $id,
        AcademyId $academyId,
        string $code,
        string $name,
        ?string $description,
        AuditTrail $auditTrail
    ): self {
        return new self($id, $academyId, $code, $name, $description, $auditTrail);
    }

    public function id(): PaymentConceptId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function code(): string { return $this->code; }
    public function name(): string { return $this->name; }
    public function description(): ?string { return $this->description; }
    public function status(): PaymentConceptStatus { return $this->status; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }

    public function update(string $name, ?string $description, string $updatedBy): void
    {
        $this->name = self::normalizeName($name);
        $this->description = self::normalizeNullableText($description);
        if ($this->auditTrail) { $this->auditTrail->touch($updatedBy); }
    }

    public function deactivate(string $updatedBy): void
    {
        $this->status = PaymentConceptStatus::inactive();
        if ($this->auditTrail) { $this->auditTrail->touch($updatedBy); }
    }

    private static function normalizeName(string $value): string
    {
        $value = trim($value);
        if ('' === $value) { throw new \InvalidArgumentException('Payment concept name cannot be empty.'); }
        if (mb_strlen($value) > 100) { throw new \InvalidArgumentException('Payment concept name is too long.'); }
        return $value;
    }
    private static function normalizeNullableText(?string $value): ?string
    {
        if (null === $value) return null;
        $value = trim($value);
        return '' === $value ? null : $value;
    }
}
