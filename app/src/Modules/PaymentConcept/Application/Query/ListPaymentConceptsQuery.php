<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Query;
use App\Modules\Academy\Domain\Academy\AcademyId;
final readonly class ListPaymentConceptsQuery { public function __construct(public AcademyId $academyId) {}}
