<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException;

final class FileTooLargeException extends DomainException
{
    public function __construct(int $maxBytes)
    {
        parent::__construct(sprintf('File exceeds the maximum allowed size of %d bytes.', $maxBytes));
    }
}
