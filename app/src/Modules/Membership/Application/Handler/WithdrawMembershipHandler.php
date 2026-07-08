<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Application\Command\WithdrawMembershipCommand;
use App\Modules\Membership\Domain\Exception\MembershipNotFoundException;
use App\Modules\Membership\Domain\Membership\MembershipRepository;

final readonly class WithdrawMembershipHandler
{
    public function __construct(
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function __invoke(WithdrawMembershipCommand $command): void
    {
        $membership = $this->membershipRepository->findActiveByPlayerId(
            new AcademyId($command->academyId),
            new \App\Modules\Player\Domain\Player\PlayerId($command->playerId)
        );

        if (null === $membership) {
            throw new MembershipNotFoundException();
        }

        $membership->withdraw($command->actorId);
        $this->membershipRepository->save($membership);
    }
}
