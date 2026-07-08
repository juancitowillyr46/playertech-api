<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Application\Command\SuspendMembershipCommand;
use App\Modules\Membership\Application\Services\MembershipFinder;
use App\Modules\Membership\Domain\Membership\MembershipRepository;

final readonly class SuspendMembershipHandler
{
    public function __construct(
        private MembershipFinder $membershipFinder,
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function __invoke(SuspendMembershipCommand $command): void
    {
        $membership = $this->membershipFinder->findActiveOrFail(
            new AcademyId($command->academyId),
            new \App\Modules\Player\Domain\Player\PlayerId($command->playerId)
        );

        $membership->suspend($command->actorId);
        $this->membershipRepository->save($membership);
    }
}
