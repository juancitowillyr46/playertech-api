<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\RequestPasswordResetInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RequestPasswordResetRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "email" es obligatorio.')]
        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        public ?string $email,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(self::stringOrNull($payload['email'] ?? null));
    }

    public function toInput(): RequestPasswordResetInput
    {
        return new RequestPasswordResetInput($this->email);
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
