<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Document;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;

interface PlayerDocumentRepository
{
    public function save(PlayerDocument $document): void;
    /** @return array{items: PlayerDocument[], total: int} */
    public function findActiveByPlayer(AcademyId $academyId, PlayerId $playerId, PaginationQuery $pagination): array;
    public function findActiveById(AcademyId $academyId, PlayerId $playerId, PlayerDocumentId $documentId): ?PlayerDocument;
}
