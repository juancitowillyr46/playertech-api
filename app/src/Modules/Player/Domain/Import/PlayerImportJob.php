<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Import;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

class PlayerImportJob implements Auditable
{
    private PlayerImportJobId $id;

    private AcademyId $academyId;

    private string $createdBy;

    private string $categoryId;

    private string $originalFileName;

    private string $filePath;

    private string $status;

    private int $progress = 0;

    private int $totalRows = 0;

    private int $processedRows = 0;

    private int $successRows = 0;

    private int $errorRows = 0;

    private array $errors = [];

    private ?\DateTimeImmutable $startedAt = null;

    private ?\DateTimeImmutable $finishedAt = null;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private ?AuditTrail $auditTrail = null;

    private function __construct()
    {
    }

    public static function create(
        PlayerImportJobId $id,
        AcademyId $academyId,
        string $createdBy,
        string $categoryId,
        string $originalFileName,
        string $filePath
    ): self {
        $self = new self();
        $self->id = $id;
        $self->academyId = $academyId;
        $self->createdBy = $createdBy;
        $self->categoryId = $categoryId;
        $self->originalFileName = $originalFileName;
        $self->filePath = $filePath;
        $self->status = PlayerImportJobStatus::queued()->value();
        $self->createdAt = new \DateTimeImmutable();
        $self->auditTrail = AuditTrail::create($createdBy);

        return $self;
    }

    public function id(): PlayerImportJobId
    {
        return $this->id;
    }

    public function academyId(): AcademyId
    {
        return $this->academyId;
    }

    public function createdBy(): string
    {
        return $this->createdBy;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function originalFileName(): string
    {
        return $this->originalFileName;
    }

    public function filePath(): string
    {
        return $this->filePath;
    }

    public function status(): PlayerImportJobStatus
    {
        return new PlayerImportJobStatus($this->status);
    }

    public function progress(): int
    {
        return $this->progress;
    }

    public function totalRows(): int
    {
        return $this->totalRows;
    }

    public function processedRows(): int
    {
        return $this->processedRows;
    }

    public function successRows(): int
    {
        return $this->successRows;
    }

    public function errorRows(): int
    {
        return $this->errorRows;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function startedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function finishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function auditTrail(): ?AuditTrail
    {
        return $this->auditTrail;
    }

    public function setAuditTrail(AuditTrail $auditTrail): void
    {
        $this->auditTrail = $auditTrail;
    }

    public function start(): void
    {
        $this->status = PlayerImportJobStatus::validating()->value();
        $this->startedAt = new \DateTimeImmutable();
        $this->touch();
    }

    public function markProcessing(): void
    {
        $this->status = PlayerImportJobStatus::processing()->value();
        $this->touch();
    }

    public function markCompleted(): void
    {
        $this->status = PlayerImportJobStatus::completed()->value();
        $this->progress = 100;
        $this->finishedAt = new \DateTimeImmutable();
        $this->touch();
    }

    public function markCompletedWithErrors(): void
    {
        $this->status = PlayerImportJobStatus::completedWithErrors()->value();
        $this->progress = 100;
        $this->finishedAt = new \DateTimeImmutable();
        $this->touch();
    }

    public function markFailed(string $message): void
    {
        $this->status = PlayerImportJobStatus::failed()->value();
        $this->finishedAt = new \DateTimeImmutable();
        $this->errors[] = [
            'row' => null,
            'field' => 'file',
            'message' => $message,
        ];
        $this->touch();
    }

    public function setProgress(int $progress): void
    {
        $this->progress = max(0, min(100, $progress));
        $this->touch();
    }

    public function setTotals(int $totalRows, int $processedRows, int $successRows, int $errorRows): void
    {
        $this->totalRows = $totalRows;
        $this->processedRows = $processedRows;
        $this->successRows = $successRows;
        $this->errorRows = $errorRows;
        $this->touch();
    }

    /**
     * @param array<int, array{row:int, field:string, message:string}> $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
        $this->errorRows = count($errors);
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if ($this->auditTrail) {
            $this->auditTrail->touch($this->createdBy);
        }
    }
}
