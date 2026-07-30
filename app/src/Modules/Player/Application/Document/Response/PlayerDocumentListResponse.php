<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Response;

use App\Shared\Application\Pagination\PaginatedResult;

final readonly class PlayerDocumentListResponse
{
    public function __construct(public PaginatedResult $result) {}
    public function items(): array
    {
        return $this->result->items;
    }
    public function meta(): array
    {
        return $this->result->meta->toArray();
    }
}
