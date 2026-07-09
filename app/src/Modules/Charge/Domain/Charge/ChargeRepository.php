<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Charge;
use App\Modules\Academy\Domain\Academy\AcademyId;
interface ChargeRepository
{
    public function save(Charge $charge): void;
    public function findById(AcademyId $academyId, ChargeId $chargeId): ?Charge;
    /** @return Charge[] */
    public function findPendingByAcademy(AcademyId $academyId): array;
}
