<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Player;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player;

    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player;

    /**
     * @return Player[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;
}
