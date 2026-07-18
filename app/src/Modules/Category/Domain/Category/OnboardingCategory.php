<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Category;

use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;

final class OnboardingCategory
{
    private string $id;
    private string $code;
    private Name $name;
    private MinimumAge $minAge;
    private MaximumAge $maxAge;
    private ?Description $description;
    private string $status;
    private ?\DateTimeImmutable $createdAt = null;
    private ?\DateTimeImmutable $updatedAt = null;

    private function __construct(
        string $id,
        string $code,
        Name $name,
        MinimumAge $minAge,
        MaximumAge $maxAge,
        ?Description $description,
        string $status
    ) {
        $this->id = $id;
        $this->code = self::normalizeCode($code);
        $this->name = $name;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->description = $description;
        $this->status = self::normalizeStatus($status);
    }

    public static function create(
        string $id,
        string $code,
        Name $name,
        MinimumAge $minAge,
        MaximumAge $maxAge,
        ?Description $description = null,
        string $status = 'ACTIVE'
    ): self {
        return new self($id, $code, $name, $minAge, $maxAge, $description, $status);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function minAge(): MinimumAge
    {
        return $this->minAge;
    }

    public function maxAge(): MaximumAge
    {
        return $this->maxAge;
    }

    public function description(): ?Description
    {
        return $this->description;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return 'ACTIVE' === $this->status;
    }

    private static function normalizeCode(string $code): string
    {
        $code = strtoupper(trim($code));

        if ('' === $code) {
            throw new \InvalidArgumentException('Onboarding category code cannot be empty.');
        }

        return $code;
    }

    private static function normalizeStatus(string $status): string
    {
        $status = strtoupper(trim($status));

        return '' === $status ? 'ACTIVE' : $status;
    }
}
