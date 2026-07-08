<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Membership\Application\Query\ShowMembershipHistoryQuery;
use App\Modules\Membership\Application\Response\MembershipHistoryItemResponse;
use App\Modules\Membership\Domain\Membership\MembershipRepository;

final readonly class ShowMembershipHistoryHandler
{
    public function __construct(
        private MembershipRepository $membershipRepository,
    ) {
    }

    /**
     * @return MembershipHistoryItemResponse[]
     */
    public function __invoke(ShowMembershipHistoryQuery $query): array
    {
        return array_map(
            static fn ($membership) => MembershipHistoryItemResponse::fromMembership($membership),
            $this->membershipRepository->findAllByPlayerId($query->academyId, $query->playerId)
        );
    }
}
