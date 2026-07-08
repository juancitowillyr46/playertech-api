<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Membership;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;

interface MembershipRepository
{
    public function save(Membership $membership): void;

    public function findActiveByPlayerId(AcademyId $academyId, PlayerId $playerId): ?Membership;

    public function findActiveByPlayerIdOrFail(AcademyId $academyId, PlayerId $playerId): Membership;

    /**
     * @return Membership[]
     */
    public function findAllByPlayerId(AcademyId $academyId, PlayerId $playerId): array;
}
