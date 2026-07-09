<?php
declare(strict_types=1);
namespace App\Modules\Dashboard\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Dashboard\Application\Query\ShowDashboardQuery;
use App\Modules\Dashboard\Application\Response\DashboardResponse;
use App\Modules\Membership\Application\Response\MembershipResponse;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Player\Application\Response\PlayerListItemResponse;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Charge\Application\Response\ChargeResponse;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\Player\Domain\Player\PlayerId;
final readonly class ShowDashboardHandler
{
    public function __construct(private PlayerRepository $playerRepository, private MembershipRepository $membershipRepository, private ChargeRepository $chargeRepository, private PaymentRepository $paymentRepository) {}
    public function __invoke(ShowDashboardQuery $query): DashboardResponse
    {
        $academyId = new AcademyId($query->academyId);
        $players = $this->playerRepository->findAllByAcademy($academyId);
        $charges = $this->chargeRepository->findPendingByAcademy($academyId);
        $payments = $this->paymentRepository->findAllByAcademy($academyId);

        $activePlayers = array_values(array_filter($players, static fn ($player) => $player->status()->isActive()));
        $memberships = [];
        foreach ($players as $player) {
            $memberships = array_merge($memberships, $this->membershipRepository->findAllByPlayerId($academyId, new PlayerId($player->id()->value())));
        }
        $activeMemberships = array_values(array_filter($memberships, static fn ($membership) => 'ACTIVE' === $membership->status()->value()));
        $pendingAmount = array_reduce($charges, static fn (float $carry, $charge) => $carry + (float) $charge->amount(), 0.0);
        $paidAmount = array_reduce($payments, static fn (float $carry, $payment) => $carry + (float) $payment->amount(), 0.0);

        return new DashboardResponse(
            count($activePlayers),
            count($charges),
            count($activeMemberships),
            number_format(max(0, $pendingAmount - $paidAmount), 2, '.', ''),
            array_map(static fn ($player) => PlayerListItemResponse::fromPlayer($player)->toArray(), $activePlayers),
            array_map(static fn ($charge) => ChargeResponse::fromCharge($charge)->toArray(), $charges),
            array_map(static fn ($membership) => MembershipResponse::fromMembership($membership)->toArray(), $activeMemberships)
        );
    }
}
