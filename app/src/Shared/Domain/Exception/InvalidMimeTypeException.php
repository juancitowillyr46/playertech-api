<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException;
use Throwable;

final class InvalidMimeTypeException extends DomainException
{
    public function __construct(string $invalidType, array $allowedTypes, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'Invalid MIME type "%s". Allowed types are: %s',
            $invalidType,
            implode(', ', $allowedTypes)
        );
        parent::__construct($message, $code, $previous);
    }
}
