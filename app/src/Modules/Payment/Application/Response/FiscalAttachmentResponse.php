<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Response;

use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachment;

final readonly class FiscalAttachmentResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $paymentId,
        private string $providerName,
        private string $documentNumber,
        private ?string $documentUrl,
        private ?string $status,
    ) {
    }

    public static function fromAttachment(FiscalAttachment $attachment): self
    {
        return new self(
            $attachment->id()->value(),
            $attachment->academyId()->value(),
            $attachment->paymentId()->value(),
            $attachment->providerName(),
            $attachment->documentNumber(),
            $attachment->documentUrl(),
            $attachment->status(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'paymentId' => $this->paymentId,
            'providerName' => $this->providerName,
            'documentNumber' => $this->documentNumber,
            'documentUrl' => $this->documentUrl,
            'status' => $this->status,
        ];
    }
}
