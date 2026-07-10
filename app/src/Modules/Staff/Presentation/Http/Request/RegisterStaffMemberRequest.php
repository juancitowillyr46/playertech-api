<?php
declare(strict_types=1);
namespace App\Modules\Staff\Presentation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class RegisterStaffMemberRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "userId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "userId" debe ser un UUID válido.')]
        public ?string $userId = null,
    ) {}
}
