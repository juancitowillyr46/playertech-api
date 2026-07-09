<?php
declare(strict_types=1);
namespace App\Modules\Staff\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class AssignStaffToTeamRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "staff_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "staff_id" debe ser un UUID válido.')]
        public ?string $staffId = null,
        #[Assert\NotBlank(message: 'El campo "team_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "team_id" debe ser un UUID válido.')]
        public ?string $teamId = null,
        #[Assert\NotBlank(message: 'El campo "role" es obligatorio.')]
        public ?string $role = null,
    ) {}
}
