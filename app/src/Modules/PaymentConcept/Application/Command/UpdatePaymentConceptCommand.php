<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Command;
use App\Modules\PaymentConcept\Application\Dto\UpdatePaymentConceptInput;
final readonly class UpdatePaymentConceptCommand { public function __construct(public string $actorId, public string $academyId, public string $paymentConceptId, public UpdatePaymentConceptInput $input) {}}
