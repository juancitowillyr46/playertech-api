<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Membership\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Player\Domain\Player\PlayerId;

final class InMemoryMembershipRepository implements MembershipRepository
{
    /** @var array<string, Membership> */
    public array $memberships = [];

    public function save(Membership $membership): void
    {
        $this->memberships[$membership->id()->value()] = $membership;
    }

    public function findActiveByPlayerId(AcademyId $academyId, PlayerId $playerId): ?Membership
    {
        foreach ($this->memberships as $membership) {
            if ($membership->academyId()->value() === $academyId->value()
                && $membership->playerId()->value() === $playerId->value()
                && 'ACTIVE' === $membership->status()->value()) {
                return $membership;
            }
        }

        return null;
    }
}
