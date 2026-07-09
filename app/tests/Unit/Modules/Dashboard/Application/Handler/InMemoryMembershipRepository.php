<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Dashboard\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Player\Domain\Player\PlayerId;
final class InMemoryMembershipRepository implements MembershipRepository
{
    /** @var Membership[] */
    public array $items = [];
    public function save(Membership $membership): void { $this->items[$membership->id()->value()] = $membership; }
    public function findActiveByPlayerId(AcademyId $academyId, PlayerId $playerId): ?Membership { foreach ($this->items as $item) { if ($item->academyId()->equals($academyId) && $item->playerId()->equals($playerId) && $item->status()->isActive()) return $item; } return null; }
    public function findActiveByPlayerIdOrFail(AcademyId $academyId, PlayerId $playerId): Membership { return $this->findActiveByPlayerId($academyId, $playerId) ?? throw new \RuntimeException('Not found'); }
    public function findAllByPlayerId(AcademyId $academyId, PlayerId $playerId): array { return array_values(array_filter($this->items, static fn (Membership $membership): bool => $membership->academyId()->equals($academyId) && $membership->playerId()->equals($playerId))); }
}
