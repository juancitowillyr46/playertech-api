<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Exception\MembershipNotFoundException;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class MembershipFinder
{
    public function __construct(
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function findActiveOrFail(AcademyId $academyId, PlayerId $playerId): Membership
    {
        $membership = $this->membershipRepository->findActiveByPlayerId($academyId, $playerId);

        if (null === $membership) {
            throw new MembershipNotFoundException();
        }

        return $membership;
    }
}
