<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Request;

use App\Modules\Academy\Application\Dto\UpdateAcademyTaxProfileInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateAcademyTaxProfileRequest
{
    public function __construct(
        #[Assert\Length(max: 40, maxMessage: 'El campo "taxIdType" excede la longitud máxima permitida.')]
        public ?string $taxIdType = null,

        #[Assert\Length(max: 40, maxMessage: 'El campo "taxIdNumber" excede la longitud máxima permitida.')]
        public ?string $taxIdNumber = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "taxRegime" excede la longitud máxima permitida.')]
        public ?string $taxRegime = null,

        #[Assert\Email(message: 'El campo "billingEmail" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "billingEmail" excede la longitud máxima permitida.')]
        public ?string $billingEmail = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['taxIdType'] ?? null),
            self::stringOrNull($payload['taxIdNumber'] ?? null),
            self::stringOrNull($payload['taxRegime'] ?? null),
            self::stringOrNull($payload['billingEmail'] ?? null),
        );
    }

    public function toInput(): UpdateAcademyTaxProfileInput
    {
        return new UpdateAcademyTaxProfileInput(
            $this->taxIdType,
            $this->taxIdNumber,
            $this->taxRegime,
            $this->billingEmail,
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
