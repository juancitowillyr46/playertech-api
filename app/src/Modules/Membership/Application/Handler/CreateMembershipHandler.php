<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Response\MembershipResponse;
use App\Modules\Membership\Domain\Exception\MembershipAlreadyExistsException;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class CreateMembershipHandler
{
    public function __construct(
        private MembershipRepository $membershipRepository,
    ) {
    }

    public function __invoke(CreateMembershipCommand $command): MembershipResponse
    {
        $academyId = new AcademyId($command->academyId);
        $playerId = new PlayerId($command->playerId);

        if (null !== $this->membershipRepository->findActiveByPlayerId($academyId, $playerId)) {
            throw new MembershipAlreadyExistsException();
        }

        $membership = Membership::create(
            MembershipId::generate(),
            $academyId,
            $playerId,
            new LegalGuardianId($command->primaryGuardianId),
            AuditTrail::create($command->actorId),
        );

        $this->membershipRepository->save($membership);

        return MembershipResponse::fromMembership($membership);
    }
}
