<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Presentation\Http\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\PaymentConcept\Application\Command\CreatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Command\DeactivatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Command\UpdatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Handler\CreatePaymentConceptHandler;
use App\Modules\PaymentConcept\Application\Handler\DeactivatePaymentConceptHandler;
use App\Modules\PaymentConcept\Application\Handler\ListPaymentConceptsHandler;
use App\Modules\PaymentConcept\Application\Handler\ShowPaymentConceptHandler;
use App\Modules\PaymentConcept\Application\Handler\UpdatePaymentConceptHandler;
use App\Modules\PaymentConcept\Application\Query\ListPaymentConceptsQuery;
use App\Modules\PaymentConcept\Application\Query\ShowPaymentConceptQuery;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Presentation\Http\Request\CreatePaymentConceptRequest;
use App\Modules\PaymentConcept\Presentation\Http\Request\UpdatePaymentConceptRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/payment-concepts')]
final class PaymentConceptController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreatePaymentConceptHandler $createHandler,
        private readonly ListPaymentConceptsHandler $listHandler,
        private readonly ShowPaymentConceptHandler $showHandler,
        private readonly UpdatePaymentConceptHandler $updateHandler,
        private readonly DeactivatePaymentConceptHandler $deactivateHandler,
        private readonly TenantContext $tenantContext,
    ) {}
    #[Route('', methods:['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreatePaymentConceptRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createHandler)(
            new CreatePaymentConceptCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $input->toInput()
            )
        );

        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()], 201);
    }
    #[Route('', methods:['GET'])]
    public function list(Request $request): JsonResponse
    {
        $items = ($this->listHandler)(new ListPaymentConceptsQuery(new AcademyId($this->tenantContext->requireAcademyId()), $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value')));
        return new JsonResponse(['data'=>array_map(static fn($i)=>$i->toArray(), $items->items),'meta'=>$items->meta->toArray()]);
    }
    #[Route('/{paymentConceptId}', methods:['GET'])]
    public function show(string $paymentConceptId): JsonResponse
    {
        $view = ($this->showHandler)(new ShowPaymentConceptQuery(new AcademyId($this->tenantContext->requireAcademyId()), new PaymentConceptId($paymentConceptId)));
        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()]);
    }
    #[Route('/{paymentConceptId}', methods:['PUT'])]
    public function update(Request $request, string $paymentConceptId): JsonResponse
    {
        $input = UpdatePaymentConceptRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateHandler)(
            new UpdatePaymentConceptCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $paymentConceptId,
                $input->toInput()
            )
        );

        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()]);
    }
    #[Route('/{paymentConceptId}/deactivate', methods:['PATCH'])]
    public function deactivate(string $paymentConceptId): Response
    {
        ($this->deactivateHandler)(new DeactivatePaymentConceptCommand($this->requireAuthenticatedUserId($this->security), $this->tenantContext->requireAcademyId(), $paymentConceptId));
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
