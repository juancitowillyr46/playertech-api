<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\PaymentConcept\Application\Query\ListPaymentConceptsQuery;
use App\Modules\PaymentConcept\Application\Response\PaymentConceptListItemResponse;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
final readonly class ListPaymentConceptsHandler
{
    public function __construct(private PaymentConceptRepository $repository) {}
    public function __invoke(ListPaymentConceptsQuery $query): array
    {
        return array_map(static fn($item) => PaymentConceptListItemResponse::fromPaymentConcept($item), $this->repository->findAllByAcademy($query->academyId));
    }
}
