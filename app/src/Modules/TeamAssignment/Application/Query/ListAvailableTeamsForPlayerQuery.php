<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Query;

final readonly class ListAvailableTeamsForPlayerQuery
{
    public function __construct(
        public string $academyId,
        public string $playerId,
        public ?string $query = null,
    ) {
    }
}
