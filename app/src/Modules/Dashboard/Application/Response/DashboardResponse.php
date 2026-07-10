<?php
declare(strict_types=1);
namespace App\Modules\Dashboard\Application\Response;
final readonly class DashboardResponse
{
    public function __construct(
        public int $activePlayers,
        public int $pendingCharges,
        public int $activeMemberships,
        public string $pendingAmount,
        public array $activePlayersList,
        public array $pendingChargesList,
        public array $activeMembershipsList
    ) {}
    public function toArray(): array
    {
        return [
            'activePlayers' => $this->activePlayers,
            'pendingCharges' => $this->pendingCharges,
            'activeMemberships' => $this->activeMemberships,
            'pendingAmount' => $this->pendingAmount,
            'activePlayersList' => $this->activePlayersList,
            'pendingChargesList' => $this->pendingChargesList,
            'activeMembershipsList' => $this->activeMembershipsList,
        ];
    }
}
