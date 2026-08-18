<?php

declare(strict_types=1);

namespace App\Modules\Membership\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateMembershipRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "playerId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "playerId" debe ser un UUID válido.')]
        public ?string $playerId = null,

        #[Assert\NotBlank(message: 'El campo "responsibleGuardianId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "responsibleGuardianId" debe ser un UUID válido.')]
        public ?string $responsibleGuardianId = null,

        #[Assert\NotBlank(message: 'El campo "categoryId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "categoryId" debe ser un UUID válido.')]
        public ?string $categoryId = null,
    ) {
    }
}
