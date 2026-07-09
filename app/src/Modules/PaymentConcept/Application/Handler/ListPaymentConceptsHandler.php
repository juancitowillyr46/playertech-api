<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\PaymentConcept\Application\Query\ListPaymentConceptsQuery;
use App\Modules\PaymentConcept\Application\Response\PaymentConceptListItemResponse;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Shared\Application\Pagination\PaginatedResult;
final readonly class ListPaymentConceptsHandler
{
    public function __construct(private PaymentConceptRepository $repository) {}
    public function __invoke(ListPaymentConceptsQuery $query): PaginatedResult
    {
        $result = $this->repository->findAllByAcademy($query->academyId, $query->pagination);
        $items = array_map(static fn($item) => PaymentConceptListItemResponse::fromPaymentConcept($item), $result['items']);
        return PaginatedResult::fromItems($items, $query->pagination, $result['total']);
    }
}
