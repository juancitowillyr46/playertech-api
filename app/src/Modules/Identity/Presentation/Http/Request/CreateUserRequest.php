<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\CreateUserInput;
use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "fullName" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "fullName" excede la longitud máxima permitida.')]
        public ?string $fullName,

        #[Assert\NotBlank(message: 'El campo "email" es obligatorio.')]
        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "email" excede la longitud máxima permitida.')]
        public ?string $email,

        #[Assert\NotBlank(message: 'El campo "password" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255, minMessage: 'El campo "password" debe tener al menos 8 caracteres.', maxMessage: 'El campo "password" excede la longitud máxima permitida.')]
        public ?string $password,

        #[Assert\NotBlank(message: 'El campo "role" es obligatorio.')]
        #[Assert\Choice(choices: [AccountUser::ROLE_ROOT, AccountUser::ROLE_ACADEMY_ADMIN, AccountUser::ROLE_COACH], message: 'El campo "role" no es válido.')]
        public ?string $role,

        #[Assert\Length(max: 36, maxMessage: 'El campo "academyId" excede la longitud máxima permitida.')]
        public ?string $academyId = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['fullName'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['role'] ?? null),
            self::stringOrNull($payload['academyId'] ?? null),
        );
    }

    public function toInput(): CreateUserInput
    {
        return new CreateUserInput(
            $this->fullName,
            $this->email,
            $this->password,
            $this->role,
            $this->academyId,
        );
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
