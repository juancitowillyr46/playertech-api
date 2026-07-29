<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Import;

final readonly class PlayerImportJobStatus
{
    public const QUEUED = 'QUEUED';
    public const VALIDATING = 'VALIDATING';
    public const PROCESSING = 'PROCESSING';
    public const COMPLETED = 'COMPLETED';
    public const COMPLETED_WITH_ERRORS = 'COMPLETED_WITH_ERRORS';
    public const FAILED = 'FAILED';

    public function __construct(private string $value)
    {
        if (!in_array($value, self::allowed(), true)) {
            throw new \InvalidArgumentException(sprintf('Invalid player import job status: %s', $value));
        }
    }

    public static function queued(): self { return new self(self::QUEUED); }
    public static function validating(): self { return new self(self::VALIDATING); }
    public static function processing(): self { return new self(self::PROCESSING); }
    public static function completed(): self { return new self(self::COMPLETED); }
    public static function completedWithErrors(): self { return new self(self::COMPLETED_WITH_ERRORS); }
    public static function failed(): self { return new self(self::FAILED); }

    public function value(): string { return $this->value; }

    public function isTerminal(): bool
    {
        return in_array($this->value, [self::COMPLETED, self::COMPLETED_WITH_ERRORS, self::FAILED], true);
    }

    /**
     * @return string[]
     */
    public static function allowed(): array
    {
        return [
            self::QUEUED,
            self::VALIDATING,
            self::PROCESSING,
            self::COMPLETED,
            self::COMPLETED_WITH_ERRORS,
            self::FAILED,
        ];
    }
}
