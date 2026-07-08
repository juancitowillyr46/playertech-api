<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\Associate;

final readonly class AssociateGuardianInput
{
    public function __construct(
        public ?string $guardianId,

        public ?bool $isPrimary = null,
    ) {
    }
}
