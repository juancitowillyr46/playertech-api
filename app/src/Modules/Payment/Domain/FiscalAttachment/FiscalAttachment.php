<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\FiscalAttachment;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class FiscalAttachment implements Auditable
{
    private FiscalAttachmentId $id;
    private AcademyId $academyId;
    private PaymentId $paymentId;
    private string $providerName;
    private string $documentNumber;
    private ?string $documentUrl;
    private ?string $status;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;

    private function __construct(
        FiscalAttachmentId $id,
        AcademyId $academyId,
        PaymentId $paymentId,
        string $providerName,
        string $documentNumber,
        ?string $documentUrl,
        ?string $status,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->paymentId = $paymentId;
        $this->providerName = trim($providerName);
        $this->documentNumber = trim($documentNumber);
        $this->documentUrl = $documentUrl;
        $this->status = null === $status ? null : strtoupper(trim($status));
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        FiscalAttachmentId $id,
        AcademyId $academyId,
        PaymentId $paymentId,
        string $providerName,
        string $documentNumber,
        ?string $documentUrl,
        ?string $status,
        AuditTrail $auditTrail
    ): self {
        return new self($id, $academyId, $paymentId, $providerName, $documentNumber, $documentUrl, $status, $auditTrail);
    }

    public function id(): FiscalAttachmentId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function paymentId(): PaymentId { return $this->paymentId; }
    public function providerName(): string { return $this->providerName; }
    public function documentNumber(): string { return $this->documentNumber; }
    public function documentUrl(): ?string { return $this->documentUrl; }
    public function status(): ?string { return $this->status; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
}
