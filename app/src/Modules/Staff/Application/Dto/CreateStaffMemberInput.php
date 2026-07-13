<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Dto;

final readonly class CreateStaffMemberInput
{
    public function __construct(
        public ?string $fullName,
        public ?string $email,
        public ?string $role,
        public ?string $password = null,
        public ?string $passwordConfirmation = null,
        public bool $sendInvitation = true,
    ) {
    }
}
