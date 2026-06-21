<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Message;

final readonly class SendTenantActivationEmailMessage
{
    public function __construct(
        public string $contactEmail,
        public string $contactName,
        public string $academyName,
        public string $activationUrl,
    ) {
    }
}
