<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Command;
use App\Modules\PaymentConcept\Application\Dto\CreatePaymentConceptInput;
final readonly class CreatePaymentConceptCommand { public function __construct(public string $actorId, public string $academyId, public CreatePaymentConceptInput $input) {}}
