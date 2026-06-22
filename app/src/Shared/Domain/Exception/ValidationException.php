<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use DomainException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationException extends DomainException
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations,
    ) {
        parent::__construct('Validation failed.');
    }

    public function violations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
