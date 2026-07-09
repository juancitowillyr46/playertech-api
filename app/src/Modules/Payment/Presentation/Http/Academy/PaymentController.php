<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Academy;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Payment\Application\Command\ApplyPaymentToChargeCommand;
use App\Modules\Payment\Application\Handler\ApplyPaymentToChargeHandler;
use App\Modules\Payment\Application\Command\RegisterPaymentCommand;
use App\Modules\Payment\Application\Handler\RegisterPaymentHandler;
use App\Modules\Payment\Presentation\Http\Request\RegisterPaymentRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/academy/payments')]
final class PaymentController extends AbstractApiController
{
    public function __construct(private readonly Security $security, private readonly ValidatorInterface $validator, private readonly RegisterPaymentHandler $registerPaymentHandler, private readonly ApplyPaymentToChargeHandler $applyPaymentToChargeHandler, private readonly TenantContext $tenantContext) {}
    #[Route('', methods:['POST'])]
    public function register(Request $request): JsonResponse
    {
        $input = new RegisterPaymentRequest($request->toArray()['membership_id'] ?? null, $request->toArray()['player_id'] ?? null, $request->toArray()['guardian_id'] ?? null, $request->toArray()['payment_concept_id'] ?? null, $request->toArray()['payment_date'] ?? null, $request->toArray()['amount'] ?? null, $request->toArray()['notes'] ?? null);
        $this->assertValid($this->validator, $input);
        $view = ($this->registerPaymentHandler)(new RegisterPaymentCommand($this->requireAuthenticatedUserId($this->security), $this->tenantContext->requireAcademyId(), $input->membershipId ?? '', $input->playerId ?? '', $input->guardianId ?? '', $input->paymentConceptId ?? '', $input->paymentDate ?? '', $input->amount ?? '', $input->notes));
        return new JsonResponse(['data'=>$view->toArray(),'meta'=>new \stdClass()], 201);
    }
    #[Route('/apply', methods:['PATCH'])]
    public function apply(Request $request): JsonResponse
    {
        $payload = $request->toArray();
        ($this->applyPaymentToChargeHandler)(new ApplyPaymentToChargeCommand(
            $this->requireAuthenticatedUserId($this->security),
            $this->tenantContext->requireAcademyId(),
            $payload['payment_id'] ?? '',
            $payload['charge_id'] ?? '',
            $payload['amount'] ?? '0'
        ));
        return new JsonResponse(['data'=>new \stdClass(),'meta'=>new \stdClass()]);
    }
}
