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
        $charges = $this->chargeRepository->findPendingByPlayer(new AcademyId($query->academyId), new PlayerId($query->playerId));
        $pendingAmount = array_reduce($charges, static fn (float $carry, $charge) => $carry + (float) $charge->pendingBalance(), 0.0);
        return new PlayerDebtResponse($query->playerId, number_format($pendingAmount, 2, '.', ''), count($charges));
    }
}
