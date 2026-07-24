<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Message;

final readonly class SendUserPasswordAndActivationEmailMessage
{
    public function __construct(
        public string $email,
        public string $fullName,
        public string $username,
        public string $password,
        public string $activationUrl,
    ) {
    }
}
