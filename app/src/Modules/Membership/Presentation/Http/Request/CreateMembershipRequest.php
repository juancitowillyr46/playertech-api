<?php

declare(strict_types=1);

namespace App\Modules\Membership\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateMembershipRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "player_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "player_id" debe ser un UUID válido.')]
        public ?string $playerId = null,

        #[Assert\NotBlank(message: 'El campo "primary_guardian_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "primary_guardian_id" debe ser un UUID válido.')]
        public ?string $primaryGuardianId = null,
    ) {
    }
}
