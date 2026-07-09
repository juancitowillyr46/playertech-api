<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Payment\Application\Query\ShowPlayerDebtQuery;
use App\Modules\Payment\Application\Response\PlayerDebtResponse;
use App\Modules\Player\Domain\Player\PlayerId;
final readonly class ShowPlayerDebtHandler
{
    public function __construct(private ChargeRepository $chargeRepository) {}
    public function __invoke(ShowPlayerDebtQuery $query): PlayerDebtResponse
    {
        $charges = $this->chargeRepository->findPendingByAcademy(new AcademyId($query->academyId));
        $pendingCharges = array_filter($charges, static fn ($charge) => $charge->membershipId() !== null);
        $pendingAmount = array_reduce($pendingCharges, static fn (float $carry, $charge) => $carry + (float) $charge->amount(), 0.0);
        return new PlayerDebtResponse($query->playerId, number_format($pendingAmount, 2, '.', ''), count($pendingCharges));
    }
}
