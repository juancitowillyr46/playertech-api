<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Query;

final readonly class ShowUserQuery
{
    public function __construct(
        public string $userId,
        public ?string $academyId = null,
    ) {
    }
}
