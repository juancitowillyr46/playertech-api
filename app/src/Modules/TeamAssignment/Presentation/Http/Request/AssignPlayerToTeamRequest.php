<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AssignPlayerToTeamRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "player_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "player_id" debe ser un UUID válido.')]
        public ?string $playerId = null,
        #[Assert\NotBlank(message: 'El campo "team_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "team_id" debe ser un UUID válido.')]
        public ?string $teamId = null,
        #[Assert\NotBlank(message: 'El campo "start_date" es obligatorio.')]
        public ?string $startDate = null,
    ) {
    }
}
