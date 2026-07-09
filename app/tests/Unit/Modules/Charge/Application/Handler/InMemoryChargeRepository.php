<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Charge\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Shared\Application\Pagination\PaginationQuery;
final class InMemoryChargeRepository implements ChargeRepository
{
    /** @var array<string, Charge> */
    public array $items = [];
    public function save(Charge $charge): void { $this->items[$charge->id()->value()] = $charge; }
    public function findById(AcademyId $academyId, ChargeId $chargeId): ?Charge { return $this->items[$chargeId->value()] ?? null; }
    public function findPendingByAcademy(AcademyId $academyId, PaginationQuery $pagination): array {
        $items = array_values(array_filter($this->items, static fn (Charge $charge): bool => $charge->academyId()->equals($academyId) && $charge->status()->isPending()));
        return ['items' => array_slice($items, ($pagination->page - 1) * $pagination->perPage, $pagination->perPage), 'total' => count($items)];
    }
}
