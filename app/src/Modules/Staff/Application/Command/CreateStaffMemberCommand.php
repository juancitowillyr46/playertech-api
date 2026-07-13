<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Command;

use App\Modules\Staff\Application\Dto\CreateStaffMemberInput;

final readonly class CreateStaffMemberCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public CreateStaffMemberInput $input,
    ) {
    }
}
