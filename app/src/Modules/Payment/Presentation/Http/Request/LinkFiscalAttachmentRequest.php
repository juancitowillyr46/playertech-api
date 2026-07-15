<?php

declare(strict_types=1);

namespace App\Modules\Payment\Presentation\Http\Request;

use App\Modules\Payment\Application\Command\LinkFiscalAttachmentCommand;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class LinkFiscalAttachmentRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "paymentId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "paymentId" debe ser un UUID válido.')]
        public ?string $paymentId = null,

        #[Assert\NotBlank(message: 'El campo "providerName" es obligatorio.')]
        #[Assert\Length(max: 120, maxMessage: 'El campo "providerName" excede la longitud máxima permitida.')]
        public ?string $providerName = null,

        #[Assert\NotBlank(message: 'El campo "documentNumber" es obligatorio.')]
        #[Assert\Length(max: 120, maxMessage: 'El campo "documentNumber" excede la longitud máxima permitida.')]
        public ?string $documentNumber = null,

        #[Assert\Length(max: 500, maxMessage: 'El campo "documentUrl" excede la longitud máxima permitida.')]
        public ?string $documentUrl = null,

        #[Assert\Length(max: 30, maxMessage: 'El campo "status" excede la longitud máxima permitida.')]
        public ?string $status = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['paymentId'] ?? null),
            self::stringOrNull($payload['providerName'] ?? null),
            self::stringOrNull($payload['documentNumber'] ?? null),
            self::stringOrNull($payload['documentUrl'] ?? null),
            self::stringOrNull($payload['status'] ?? null),
        );
    }

    public function toCommand(string $actorId, string $academyId): LinkFiscalAttachmentCommand
    {
        return new LinkFiscalAttachmentCommand(
            $actorId,
            $academyId,
            $this->paymentId,
            $this->providerName,
            $this->documentNumber,
            $this->documentUrl,
            $this->status,
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
