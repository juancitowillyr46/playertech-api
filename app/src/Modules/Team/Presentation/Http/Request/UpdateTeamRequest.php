<?php

declare(strict_types=1);

namespace App\Modules\Team\Presentation\Http\Request;

use App\Modules\Team\Application\Dto\UpdateTeamInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateTeamRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "categoryId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "categoryId" debe ser un UUID válido.')]
        public ?string $categoryId,

        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['categoryId'] ?? null),
            self::stringOrNull($payload['name'] ?? null),
        );
    }

    public function toInput(): UpdateTeamInput
    {
        return new UpdateTeamInput(
            $this->categoryId,
            $this->name,
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
