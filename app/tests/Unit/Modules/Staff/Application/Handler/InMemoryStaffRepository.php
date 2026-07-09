<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
final class InMemoryStaffRepository implements StaffRepository
{
    /** @var array<string, Staff> */
    public array $items = [];
    public function save(Staff $staff): void { $this->items[$staff->id()->value()] = $staff; }
    public function findByUserId(AcademyId $academyId, string $userId): ?Staff { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->userId()===$userId) return $item; } return null; }
    public function findById(AcademyId $academyId, StaffId $staffId): ?Staff { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->id()->value()===$staffId->value()) return $item; } return null; }
    public function findAllByAcademy(AcademyId $academyId): array { return array_values(array_filter($this->items, fn ($item) => $item->academyId()->value()===$academyId->value())); }
}
