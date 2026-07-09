<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\Staff;
use App\Modules\Academy\Domain\Academy\AcademyId;
interface StaffRepository { public function save(Staff $staff): void; public function findByUserId(AcademyId $academyId, string $userId): ?Staff; public function findById(AcademyId $academyId, StaffId $staffId): ?Staff; public function findAllByAcademy(AcademyId $academyId): array; }
