<?php

declare(strict_types=1);

namespace App\Modules\Staff\Presentation\Http\Request;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Dto\CreateStaffMemberInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateStaffMemberRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "fullName" es obligatorio.')]
        #[Assert\Length(max: 150)]
        public ?string $fullName,

        #[Assert\NotBlank(message: 'El campo "email" es obligatorio.')]
        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        #[Assert\Length(max: 180)]
        public ?string $email,

        #[Assert\NotBlank(message: 'El campo "role" es obligatorio.')]
        #[Assert\Choice(choices: [AccountUser::ROLE_ACADEMY_ADMIN, AccountUser::ROLE_COACH], message: 'El campo "role" no es válido.')]
        public ?string $role,

        public bool $sendInvitation = true,

        #[Assert\Length(min: 8, max: 255)]
        public ?string $password = null,

        #[Assert\Length(min: 8, max: 255)]
        public ?string $passwordConfirmation = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['fullName'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
            self::stringOrNull($payload['role'] ?? null),
            self::boolOrTrue($payload['sendInvitation'] ?? true),
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['passwordConfirmation'] ?? null),
        );
    }

    public function toInput(): CreateStaffMemberInput
    {
        return new CreateStaffMemberInput(
            $this->fullName,
            $this->email,
            $this->role,
            $this->password,
            $this->passwordConfirmation,
            $this->sendInvitation,
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

    private static function boolOrTrue(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            return in_array(strtolower(trim($value)), ['1', 'true', 'yes', 'on'], true);
        }

        return (bool) $value;
    }
}
