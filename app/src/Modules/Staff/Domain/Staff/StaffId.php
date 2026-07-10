<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\Staff;
use Symfony\Component\Uid\Uuid;
final class StaffId extends Uuid
{
    public static function generate(): self
    {
        return new self(Uuid::v7()->toRfc4122());
    }

    public function value(): string
    {
        return $this->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
