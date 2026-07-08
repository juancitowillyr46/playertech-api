<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class PaymentConceptNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('Payment concept not found.');
    }
}
