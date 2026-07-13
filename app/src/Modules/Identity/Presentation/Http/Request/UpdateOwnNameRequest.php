<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Request;

use App\Modules\Identity\Application\Dto\UpdateOwnNameInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateOwnNameRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "fullName" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "fullName" excede la longitud máxima permitida.')]
        public ?string $fullName,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(self::stringOrNull($payload['fullName'] ?? null));
    }

    public function toInput(): UpdateOwnNameInput
    {
        return new UpdateOwnNameInput($this->fullName);
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
