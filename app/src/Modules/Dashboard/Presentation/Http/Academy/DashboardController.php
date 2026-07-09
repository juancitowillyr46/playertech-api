<?php
declare(strict_types=1);
namespace App\Modules\Dashboard\Presentation\Http\Academy;
use App\Modules\Dashboard\Application\Handler\ShowDashboardHandler;
use App\Modules\Dashboard\Application\Query\ShowDashboardQuery;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/academy/dashboard')]
final class DashboardController extends AbstractApiController
{
    public function __construct(private readonly Security $security, private readonly ShowDashboardHandler $handler, private readonly TenantContext $tenantContext) {}
    #[Route('', methods:['GET'])]
    public function show(): JsonResponse
    {
        $view = ($this->handler)(new ShowDashboardQuery($this->tenantContext->requireAcademyId()));
        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()]);
    }
}
