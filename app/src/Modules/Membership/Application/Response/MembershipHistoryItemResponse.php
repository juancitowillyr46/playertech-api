<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Response;

use App\Modules\Membership\Domain\Membership\Membership;

final readonly class MembershipHistoryItemResponse
{
    public function __construct(
        public string $id,
        public string $status,
        public string $startedAt,
        public ?string $endedAt,
        public string $primaryGuardianId,
    ) {
    }

    public static function fromMembership(Membership $membership): self
    {
        return new self(
            $membership->id()->value(),
            $membership->status()->value(),
            $membership->startedAt()->format(DATE_ATOM),
            $membership->endedAt()?->format(DATE_ATOM),
            $membership->primaryGuardianId()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'startedAt' => $this->startedAt,
            'endedAt' => $this->endedAt,
            'primaryGuardianId' => $this->primaryGuardianId,
        ];
    }
}
