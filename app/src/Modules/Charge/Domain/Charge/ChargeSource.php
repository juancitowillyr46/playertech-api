<?php

declare(strict_types=1);

namespace App\Modules\Charge\Domain\Charge;

final readonly class ChargeSource
{
    private function __construct(private string $value)
    {
    }

    public static function manual(): self
    {
        return new self('MANUAL');
    }

    public static function automatic(): self
    {
        return new self('AUTOMATIC');
    }

    public function value(): string
    {
        return $this->value;
    }
}
