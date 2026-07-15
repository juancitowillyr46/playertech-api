<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Command;

final readonly class LinkFiscalAttachmentCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $paymentId,
        public string $providerName,
        public string $documentNumber,
        public ?string $documentUrl = null,
        public ?string $status = null,
    ) {
    }
}
