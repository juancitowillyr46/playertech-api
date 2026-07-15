<?php

declare(strict_types=1);

namespace App\Modules\Payment\Presentation\Http\Academy;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Payment\Application\Handler\LinkFiscalAttachmentHandler;
use App\Modules\Payment\Presentation\Http\Request\LinkFiscalAttachmentRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/fiscal-attachments')]
final class FiscalAttachmentController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly LinkFiscalAttachmentHandler $handler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', methods:['POST'])]
    public function link(Request $request): JsonResponse
    {
        $input = LinkFiscalAttachmentRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->handler)(
            $input->toCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
            )
        );

        return new JsonResponse(['data' => $view->toArray(), 'meta' => new \stdClass()], 201);
    }
}
