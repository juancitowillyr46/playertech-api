<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Message;

final readonly class SendUserInvitationEmailMessage
{
    public function __construct(
        public string $email,
        public string $fullName,
        public string $activationUrl,
    ) {
    }
}
