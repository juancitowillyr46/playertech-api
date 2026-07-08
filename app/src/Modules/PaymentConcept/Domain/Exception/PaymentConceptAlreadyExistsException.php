<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class PaymentConceptAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('The payment concept already exists.');
    }
}
