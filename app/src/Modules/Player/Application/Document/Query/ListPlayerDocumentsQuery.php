<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListPlayerDocumentsQuery
{
    public function __construct(public AcademyId $academyId, public PlayerId $playerId, public PaginationQuery $pagination) {}
}
