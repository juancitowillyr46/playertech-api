<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Application\Command\WithdrawMembershipCommand;
use App\Modules\Membership\Application\Services\MembershipFinder;
use App\Modules\Membership\Domain\Membership\MembershipRepository;

final readonly class WithdrawMembershipHandler
{
    public function __construct(
        private MembershipFinder $membershipFinder,
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function __invoke(WithdrawMembershipCommand $command): void
    {
        $membership = $this->membershipFinder->findActiveOrFail(
            new AcademyId($command->academyId),
            new \App\Modules\Player\Domain\Player\PlayerId($command->playerId)
        );

        $membership->withdraw($command->actorId);
        $this->membershipRepository->save($membership);
    }
}
