<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\ListByPlayer;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class ListPlayerGuardiansQuery
{
    public function __construct(
        public AcademyId $academyId,
        public PlayerId $playerId,
    ) {
    }
}
