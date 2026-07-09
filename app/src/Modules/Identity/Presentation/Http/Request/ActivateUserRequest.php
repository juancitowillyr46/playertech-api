<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\ActivateUserInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ActivateUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "password" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255)]
        public ?string $password,

        #[Assert\NotBlank(message: 'El campo "password_confirmation" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255)]
        public ?string $passwordConfirmation,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['password_confirmation'] ?? null),
        );
    }

    public function toInput(): ActivateUserInput
    {
        return new ActivateUserInput($this->password, $this->passwordConfirmation);
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
