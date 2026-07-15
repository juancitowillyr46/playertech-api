<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\FiscalAttachment;

final readonly class FiscalAttachmentId
{
    public function __construct(private string $value)
    {
    }

    public static function generate(): self
    {
        return new self(\Symfony\Component\Uid\Uuid::v4()->toRfc4122());
    }

    public function value(): string
    {
        return $this->value;
    }
}
