<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Response;

use App\Modules\Membership\Domain\Membership\Membership;

final readonly class MembershipResponse
{
    public function __construct(
        public string $id,
        public string $academyId,
        public string $playerId,
        public string $primaryGuardianId,
        public string $status,
        public string $startedAt,
        public ?string $endedAt,
    ) {
    }

    public static function fromMembership(Membership $membership): self
    {
        return new self(
            $membership->id()->value(),
            $membership->academyId()->value(),
            $membership->playerId()->value(),
            $membership->primaryGuardianId()->value(),
            $membership->status()->value(),
            $membership->startedAt()->format(DATE_ATOM),
            $membership->endedAt()?->format(DATE_ATOM),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academy_id' => $this->academyId,
            'player_id' => $this->playerId,
            'primary_guardian_id' => $this->primaryGuardianId,
            'status' => $this->status,
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
        ];
    }
}
