<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class TeamAssignmentIdType extends AbstractUuidType
{
    public const NAME = 'team_assignment_id';

    protected function getValueObjectClass(): string
    {
        return TeamAssignmentId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
