<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\CreateLegalGuardianCommand;
use App\Modules\Guardian\Application\Handler\CreateLegalGuardianHandler;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Guardian\Presentation\Http\Request\CreateLegalGuardianRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/guardians')]
final class GuardianController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateLegalGuardianHandler $createLegalGuardianHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_guardians_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateLegalGuardianRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createLegalGuardianHandler)(
            new CreateLegalGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }
}
