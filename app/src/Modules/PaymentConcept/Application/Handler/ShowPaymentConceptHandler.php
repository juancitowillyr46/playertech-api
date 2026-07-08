<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\PaymentConcept\Application\Query\ShowPaymentConceptQuery;
use App\Modules\PaymentConcept\Application\Response\PaymentConceptResponse;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder;
final readonly class ShowPaymentConceptHandler
{
    public function __construct(private PaymentConceptFinder $finder) {}
    public function __invoke(ShowPaymentConceptQuery $query): PaymentConceptResponse
    {
        return PaymentConceptResponse::fromPaymentConcept($this->finder->findOrFail($query->academyId, $query->paymentConceptId));
    }
}
