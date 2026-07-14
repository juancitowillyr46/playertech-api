<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Application\Dto;

final readonly class UpdatePaymentConceptInput
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
    ) {
    }
}
