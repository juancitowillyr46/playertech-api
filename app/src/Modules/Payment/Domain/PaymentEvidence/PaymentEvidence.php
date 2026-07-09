<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\PaymentEvidence;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class PaymentEvidence implements Auditable
{
    private PaymentEvidenceId $id; private AcademyId $academyId; private PaymentId $paymentId; private string $fileName; private string $filePath; private string $mimeType; private ?AuditTrail $auditTrail = null; private ?\DateTimeImmutable $deletedAt = null; private ?string $deletedBy = null;
    private function __construct(PaymentEvidenceId $id, AcademyId $academyId, PaymentId $paymentId, string $fileName, string $filePath, string $mimeType, AuditTrail $auditTrail){ $this->id=$id; $this->academyId=$academyId; $this->paymentId=$paymentId; $this->fileName=$fileName; $this->filePath=$filePath; $this->mimeType=$mimeType; $this->auditTrail=$auditTrail; }
    public static function create(PaymentEvidenceId $id, AcademyId $academyId, PaymentId $paymentId, string $fileName, string $filePath, string $mimeType, AuditTrail $auditTrail): self { return new self($id,$academyId,$paymentId,$fileName,$filePath,$mimeType,$auditTrail); }
    public function id(): PaymentEvidenceId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function paymentId(): PaymentId { return $this->paymentId; }
    public function fileName(): string { return $this->fileName; }
    public function filePath(): string { return $this->filePath; }
    public function mimeType(): string { return $this->mimeType; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }
}
