<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Response;
final readonly class PlayerDebtResponse
{
    public function __construct(public string $playerId, public string $pendingAmount, public int $pendingCharges) {}
    public function toArray(): array
    {
        return ['player_id'=>$this->playerId,'pending_amount'=>$this->pendingAmount,'pending_charges'=>$this->pendingCharges];
    }
}
