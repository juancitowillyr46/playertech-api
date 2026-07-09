<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Academy;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Payment\Application\Handler\ShowPaymentHistoryHandler;
use App\Modules\Payment\Application\Handler\ShowPlayerDebtHandler;
use App\Modules\Payment\Application\Query\ShowPaymentHistoryQuery;
use App\Modules\Payment\Application\Query\ShowPlayerDebtQuery;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/academy/payments')]
final class PaymentQueryController extends AbstractApiController
{
    public function __construct(private readonly Security $security, private readonly ShowPlayerDebtHandler $debtHandler, private readonly ShowPaymentHistoryHandler $historyHandler, private readonly TenantContext $tenantContext) {}
    #[Route('/players/{playerId}/debt', methods:['GET'])]
    public function debt(string $playerId): JsonResponse
    {
        $view = ($this->debtHandler)(new ShowPlayerDebtQuery($this->tenantContext->requireAcademyId(), $playerId));
        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()]);
    }
    #[Route('/players/{playerId}/history', methods:['GET'])]
    public function history(string $playerId): JsonResponse
    {
        $items = ($this->historyHandler)(new ShowPaymentHistoryQuery($this->tenantContext->requireAcademyId(), $playerId));
        return new JsonResponse(['data'=>array_map(static fn($item) => $item->toArray(), $items),'meta'=>new \stdClass()]);
    }
}
