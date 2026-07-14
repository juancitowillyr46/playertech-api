<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Charge;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;
interface ChargeRepository
{
    public function save(Charge $charge): void;
    public function findById(AcademyId $academyId, ChargeId $chargeId): ?Charge;
    /** @return Charge[] */
    public function findPendingByPlayer(AcademyId $academyId, PlayerId $playerId): array;
    /** @return array{items: Charge[], total: int} */
    public function findPendingByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;
}
