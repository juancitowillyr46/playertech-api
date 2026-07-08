<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Command;
final readonly class DeactivatePaymentConceptCommand { public function __construct(public string $actorId, public string $academyId, public string $paymentConceptId) {}}
