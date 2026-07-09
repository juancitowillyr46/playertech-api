<?php
declare(strict_types=1);
namespace App\Modules\Dashboard\Application\Query;
final readonly class ShowDashboardQuery
{
    public function __construct(public string $academyId) {}
}
