<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Request;

use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AssociateGuardianRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "guardian_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "guardian_id" debe ser un UUID válido.')]
        public ?string $guardianId,

        public ?bool $isPrimary = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['guardian_id'] ?? null),
            self::boolOrNull($payload['is_primary'] ?? null),
        );
    }

    public function toInput(): AssociateGuardianInput
    {
        return new AssociateGuardianInput(
            $this->guardianId,
            $this->isPrimary,
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

    private static function boolOrNull(mixed $value): ?bool
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
