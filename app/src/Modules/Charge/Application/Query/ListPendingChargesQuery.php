<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Query;
final readonly class ListPendingChargesQuery
{
    public function __construct(public string $academyId) {}
}
