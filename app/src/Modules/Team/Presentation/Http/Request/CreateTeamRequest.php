<?php

declare(strict_types=1);

namespace App\Modules\Team\Presentation\Http\Request;

use App\Modules\Team\Application\Dto\CreateTeamInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateTeamRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "category_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "category_id" debe ser un UUID válido.')]
        public ?string $categoryId,

        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 80, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['category_id'] ?? null),
            self::stringOrNull($payload['name'] ?? null),
        );
    }

    public function toInput(): CreateTeamInput
    {
        return new CreateTeamInput(
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
