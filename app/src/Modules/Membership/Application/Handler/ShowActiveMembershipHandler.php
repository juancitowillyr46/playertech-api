<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Membership\Application\Query\ShowActiveMembershipQuery;
use App\Modules\Membership\Application\Response\MembershipResponse;
use App\Modules\Membership\Application\Services\MembershipFinder;

final readonly class ShowActiveMembershipHandler
{
    public function __construct(
        private MembershipFinder $membershipFinder,
    ) {
    }

    public function __invoke(ShowActiveMembershipQuery $query): MembershipResponse
    {
        $membership = $this->membershipFinder->findActiveOrFail($query->academyId, $query->playerId);

        return MembershipResponse::fromMembership($membership);
    }
}
