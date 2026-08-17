<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AssignPlayerToTeamRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "playerId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "playerId" debe ser un UUID válido.')]
        public ?string $playerId = null,
        #[Assert\NotBlank(message: 'El campo "teamId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "teamId" debe ser un UUID válido.')]
        public ?string $teamId = null,
        public ?bool $isPrimary = null,
    ) {
    }
}
