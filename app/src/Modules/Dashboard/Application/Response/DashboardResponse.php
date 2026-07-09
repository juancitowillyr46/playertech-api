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
            'active_players' => $this->activePlayers,
            'pending_charges' => $this->pendingCharges,
            'active_memberships' => $this->activeMemberships,
            'pending_amount' => $this->pendingAmount,
            'active_players_list' => $this->activePlayersList,
            'pending_charges_list' => $this->pendingChargesList,
            'active_memberships_list' => $this->activeMembershipsList,
        ];
    }
}
