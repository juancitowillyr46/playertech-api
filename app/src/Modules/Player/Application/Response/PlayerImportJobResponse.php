<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Import\PlayerImportJob;

final readonly class PlayerImportJobResponse
{
    public function __construct(
        private string $jobId,
        private string $status,
        private int $progress,
        private array $summary,
        private array $errors,
    ) {
    }

    public static function fromJob(PlayerImportJob $job): self
    {
        return new self(
            $job->id()->value(),
            $job->status()->value(),
            $job->progress(),
            [
                'totalRows' => $job->totalRows(),
                'processedRows' => $job->processedRows(),
                'successRows' => $job->successRows(),
                'errorRows' => $job->errorRows(),
            ],
            array_map(
                static fn (array $error): array => PlayerImportJobErrorResponse::fromArray($error)->toArray(),
                $job->errors()
            )
        );
    }

    public function toArray(): array
    {
        return [
            'jobId' => $this->jobId,
            'status' => $this->status,
            'progress' => $this->progress,
            'summary' => $this->summary,
            'errors' => $this->errors,
        ];
    }
}
