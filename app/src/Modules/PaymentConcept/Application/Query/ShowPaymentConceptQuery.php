<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Query;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
final readonly class ShowPaymentConceptQuery { public function __construct(public AcademyId $academyId, public PaymentConceptId $paymentConceptId) {}}
