<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Response;

final readonly class StaffListItemResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $userId,
        private ?string $fullName,
        private string $email,
        private string $role,
        private string $status,
        private string $userStatus,
    ) {
    }

    /**
     * @param array{
     *     id:string,
     *     academyId:string,
     *     userId:string,
     *     fullName:?string,
     *     email:string,
     *     role:string,
     *     status:string,
     *     userStatus:string
     * } $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            self::toString($row['id'] ?? null),
            self::toString($row['academyId'] ?? null),
            self::toString($row['userId'] ?? null),
            isset($row['fullName']) ? self::toNullableString($row['fullName']) : null,
            self::toString($row['email'] ?? null),
            self::toString($row['role'] ?? null),
            self::toString($row['status'] ?? null),
            self::toString($row['userStatus'] ?? null),
        );
    }

    private static function toString(mixed $value): string
    {
        if (is_string($value) || is_int($value) || is_float($value) || is_bool($value)) {
            return (string) $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('Unexpected staff list row value.');
    }

    private static function toNullableString(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return self::toString($value);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'userId' => $this->userId,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'userStatus' => $this->userStatus,
        ];
    }
}
