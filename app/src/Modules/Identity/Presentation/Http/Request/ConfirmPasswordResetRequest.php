<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\ConfirmPasswordResetInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ConfirmPasswordResetRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "password" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255, minMessage: 'La contraseña debe tener al menos 8 caracteres.', maxMessage: 'La contraseña excede la longitud máxima permitida.')]
        public ?string $password,
        #[Assert\NotBlank(message: 'El campo "passwordConfirmation" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255, minMessage: 'La contraseña debe tener al menos 8 caracteres.', maxMessage: 'La contraseña excede la longitud máxima permitida.')]
        public ?string $passwordConfirmation,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['passwordConfirmation'] ?? null),
        );
    }

    public function toInput(): ConfirmPasswordResetInput
    {
        return new ConfirmPasswordResetInput($this->password, $this->passwordConfirmation);
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
