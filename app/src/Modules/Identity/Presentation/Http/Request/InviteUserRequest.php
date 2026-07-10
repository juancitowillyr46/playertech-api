<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\InviteUserInput;
use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class InviteUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "fullName" es obligatorio.')]
        #[Assert\Length(max: 150)]
        public ?string $fullName,

        #[Assert\NotBlank(message: 'El campo "email" es obligatorio.')]
        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        #[Assert\Length(max: 180)]
        public ?string $email,

        #[Assert\NotBlank(message: 'El campo "role" es obligatorio.')]
        #[Assert\Choice(choices: [AccountUser::ROLE_ACADEMY_ADMIN], message: 'El campo "role" no es válido.')]
        public ?string $role,

        #[Assert\Length(max: 36)]
        public ?string $academyId = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['fullName'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
            self::stringOrNull($payload['role'] ?? null),
            self::stringOrNull($payload['academyId'] ?? null),
        );
    }

    public function toInput(): InviteUserInput
    {
        return new InviteUserInput($this->fullName, $this->email, $this->role, $this->academyId);
    }

    private static function stringOrNull(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim((string) $value);

        return '' === $value ? null : $value;
    }
}
