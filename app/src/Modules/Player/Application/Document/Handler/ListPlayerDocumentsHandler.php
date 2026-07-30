<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Handler;

use App\Modules\Player\Application\Document\Query\ListPlayerDocumentsQuery;
use App\Modules\Player\Application\Document\Response\PlayerDocumentItemResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Document\PlayerDocumentRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListPlayerDocumentsHandler
{
    public function __construct(private PlayerDocumentRepository $repository, private PlayerFinder $playerFinder) {}
    public function __invoke(ListPlayerDocumentsQuery $query): PaginatedResult
    {
        $this->playerFinder->findOrFail($query->academyId, $query->playerId);
        $result = $this->repository->findActiveByPlayer($query->academyId, $query->playerId, $query->pagination);
        return PaginatedResult::fromItems(array_map(static fn($d) => PlayerDocumentItemResponse::fromDocument($d), $result['items']), $query->pagination, $result['total']);
    }
}
