<?php
declare(strict_types=1);
namespace App\Modules\Staff\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class ChangeStaffRoleRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "role" es obligatorio.')]
        public ?string $role = null,
    ) {}
}
