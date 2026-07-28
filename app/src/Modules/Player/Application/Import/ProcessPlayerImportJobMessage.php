<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Import;

final readonly class ProcessPlayerImportJobMessage
{
    public function __construct(
        public string $academyId,
        public string $jobId,
        public string $actorId,
        public string $categoryId,
    ) {
    }
}
