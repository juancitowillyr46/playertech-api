<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Query;
final readonly class ShowPaymentHistoryQuery
{
    public function __construct(public string $academyId, public string $playerId) {}
}
