<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Presentation\Http\Request;

use App\Modules\PaymentConcept\Application\Dto\UpdatePaymentConceptInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatePaymentConceptRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 100)]
        public ?string $name = null,

        #[Assert\Length(max: 250)]
        public ?string $description = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['description'] ?? null),
        );
    }

    public function toInput(): UpdatePaymentConceptInput
    {
        return new UpdatePaymentConceptInput(
            $this->name,
            $this->description,
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
