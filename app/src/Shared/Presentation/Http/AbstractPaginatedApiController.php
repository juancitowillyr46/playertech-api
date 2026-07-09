<?php
declare(strict_types=1);
namespace App\Shared\Presentation\Http;
use App\Shared\Application\Pagination\PaginationQuery;
use Symfony\Component\HttpFoundation\Request;
abstract class AbstractPaginatedApiController extends AbstractApiController
{
    protected function paginationQueryFromRequest(Request $request, string $defaultSort = 'created_at'): PaginationQuery
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $perPage = min(100, max(1, (int) $request->query->get('per_page', 20)));
        $sort = (string) $request->query->get('sort', $defaultSort);
        $direction = strtoupper((string) $request->query->get('direction', 'DESC'));

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'DESC';
        }

        return new PaginationQuery($page, $perPage, $sort, $direction);
    }
}
