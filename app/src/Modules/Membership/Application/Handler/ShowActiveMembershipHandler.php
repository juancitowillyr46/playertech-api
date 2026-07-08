<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Membership\Application\Query\ShowActiveMembershipQuery;
use App\Modules\Membership\Application\Response\MembershipResponse;
use App\Modules\Membership\Domain\Exception\MembershipNotFoundException;
use App\Modules\Membership\Domain\Membership\MembershipRepository;

final readonly class ShowActiveMembershipHandler
{
    public function __construct(
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function __invoke(ShowActiveMembershipQuery $query): MembershipResponse
    {
        $membership = $this->membershipRepository->findActiveByPlayerId($query->academyId, $query->playerId);

        if (null === $membership) {
            throw new MembershipNotFoundException();
        }

        return MembershipResponse::fromMembership($membership);
    }
}
