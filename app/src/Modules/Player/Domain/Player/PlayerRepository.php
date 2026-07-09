<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Player;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Application\Pagination\PaginationQuery;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player;

    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player;

    /**
     * @return array{items: Player[], total: int}
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;
}
