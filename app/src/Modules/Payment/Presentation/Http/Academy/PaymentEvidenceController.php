<?php
declare(strict_types=1);
namespace App\Modules\Payment\Presentation\Http\Academy;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Payment\Application\Command\UploadPaymentEvidenceCommand;
use App\Modules\Payment\Application\Handler\UploadPaymentEvidenceHandler;
use App\Modules\Payment\Presentation\Http\Request\UploadPaymentEvidenceRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
#[Route('/academy/payment-evidences')]
final class PaymentEvidenceController extends AbstractApiController
{
    public function __construct(private readonly Security $security, private readonly ValidatorInterface $validator, private readonly UploadPaymentEvidenceHandler $handler, private readonly TenantContext $tenantContext) {}
    #[Route('', methods:['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $payload = $request->toArray();
        $input = new UploadPaymentEvidenceRequest($payload['paymentId'] ?? null, $payload['fileName'] ?? null, $payload['filePath'] ?? null, $payload['mimeType'] ?? null);
        $this->assertValid($this->validator, $input);
        ($this->handler)(new UploadPaymentEvidenceCommand($this->requireAuthenticatedUserId($this->security), $this->tenantContext->requireAcademyId(), $input->paymentId ?? '', $input->fileName ?? '', $input->filePath ?? '', $input->mimeType ?? ''));
        return new JsonResponse(['data'=>new \stdClass(),'meta'=>new \stdClass()], Response::HTTP_CREATED);
    }
}
