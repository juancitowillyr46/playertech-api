<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Player\Domain\Player\PlayerId;
final class InMemoryChargeRepository implements ChargeRepository
{
    /** @var array<string, Charge> */
    public array $items = [];
    public function save(Charge $charge): void { $this->items[$charge->id()->value()] = $charge; }
    public function findById(AcademyId $academyId, ChargeId $chargeId): ?Charge { return $this->items[$chargeId->value()] ?? null; }
    public function findPendingByPlayer(AcademyId $academyId, PlayerId $playerId): array { return array_values(array_filter($this->items, static fn (Charge $charge): bool => $charge->academyId()->equals($academyId) && $charge->playerId()->equals($playerId) && $charge->status()->isPending())); }
    public function findPendingByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array { return ['items' => array_values(array_filter($this->items, static fn (Charge $charge): bool => $charge->academyId()->equals($academyId) && $charge->status()->isPending())), 'total' => count(array_filter($this->items, static fn (Charge $charge): bool => $charge->academyId()->equals($academyId) && $charge->status()->isPending()))]; }
}
