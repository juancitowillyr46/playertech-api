<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\PlayerGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\PlayerId;

interface PlayerGuardianRepository
{
    public function save(PlayerGuardian $playerGuardian): void;

    public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian;

    public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian;

    /**
     * @return PlayerGuardian[]
     */
    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array;

    /**
     * @return PlayerGuardian[]
     */
    public function findAllByGuardian(AcademyId $academyId, \App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId $guardianId): array;

    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian;

    public function remove(PlayerGuardian $playerGuardian): void;
}
