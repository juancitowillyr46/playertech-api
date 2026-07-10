<?php
declare(strict_types=1);
namespace App\Modules\Charge\Presentation\Http\Academy;
use App\Modules\Charge\Application\Handler\CreateChargeHandler;
use App\Modules\Charge\Application\Handler\ListPendingChargesHandler;
use App\Modules\Charge\Application\Query\ListPendingChargesQuery;
use App\Modules\Charge\Application\Command\CreateChargeCommand;
use App\Modules\Charge\Presentation\Http\Request\CreateChargeRequest;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/academy/charges')]
final class ChargeController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateChargeHandler $createHandler,
        private readonly ListPendingChargesHandler $listPendingHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', methods:['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = new CreateChargeRequest(...$request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createHandler)(
            new CreateChargeCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $input->membershipId,
                $input->paymentConceptId,
                $input->description,
                $input->amount,
            )
        );

        return new JsonResponse(['data' => $view->toArray(), 'meta' => new \stdClass()], 201);
    }

    #[Route('/pending', methods:['GET'])]
    public function pending(Request $request): JsonResponse
    {
        $items = ($this->listPendingHandler)(
            new ListPendingChargesQuery(
                $this->tenantContext->requireAcademyId(),
                $this->paginationQueryFromRequest($request, 'created_at')
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $items->items),
            'meta' => $items->meta->toArray(),
        ]);
    }
}
