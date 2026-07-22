<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http;

use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Command\UpdateAcademyTaxProfileCommand;
use App\Modules\Academy\Application\Handler\GetAcademyContextHandler;
use App\Modules\Academy\Application\Handler\ShowAcademyHandler;
use App\Modules\Academy\Application\Handler\ShowAcademyTaxProfileHandler;
use App\Modules\Academy\Application\Handler\UpdateAcademyHandler;
use App\Modules\Academy\Application\Handler\UpdateAcademyTaxProfileHandler;
use App\Modules\Academy\Application\Query\GetAcademyContextQuery;
use App\Modules\Academy\Application\Query\ShowAcademyQuery;
use App\Modules\Academy\Application\Query\ShowAcademyTaxProfileQuery;
use App\Modules\Academy\Application\Shield\Delete\DeleteAcademyShieldCommand;
use App\Modules\Academy\Application\Shield\Delete\DeleteAcademyShieldHandler;
use App\Modules\Academy\Application\Shield\Upload\UploadAcademyShieldCommand;
use App\Modules\Academy\Application\Shield\Upload\UploadAcademyShieldHandler;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Academy\Presentation\Http\Request\UpdateAcademyRequest;
use App\Modules\Academy\Presentation\Http\Request\UpdateAcademyTaxProfileRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AcademyMeController extends AbstractApiController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly GetAcademyContextHandler $getAcademyContextHandler,
        private readonly ShowAcademyHandler $showAcademyHandler,
        private readonly ShowAcademyTaxProfileHandler $showAcademyTaxProfileHandler,
        private readonly UpdateAcademyHandler $updateAcademyHandler,
        private readonly UpdateAcademyTaxProfileHandler $updateAcademyTaxProfileHandler,
        private readonly DeleteAcademyShieldHandler $deleteAcademyShieldHandler,
        private readonly UploadAcademyShieldHandler $uploadAcademyShieldHandler,
    ) {
    }

    #[Route('/academy/context', name: 'api_v1_academy_context', methods: ['GET'])]
    public function getAcademyContext(): JsonResponse
    {
        $view = ($this->getAcademyContextHandler)(new GetAcademyContextQuery());

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me', name: 'api_v1_academy_me', methods: ['GET'])]
    public function showAcademy(TenantContext $tenantContext): JsonResponse
    {
        $view = ($this->showAcademyHandler)(new ShowAcademyQuery(
            $tenantContext->requireAcademyId()
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me/tax-profile', name: 'api_v1_academy_me_tax_profile_show', methods: ['GET'])]
    public function showTaxProfile(TenantContext $tenantContext): JsonResponse
    {
        $view = ($this->showAcademyTaxProfileHandler)(new ShowAcademyTaxProfileQuery(
            $tenantContext->requireAcademyId()
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me', name: 'api_v1_academy_me_update', methods: ['PUT'])]
    public function update(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = UpdateAcademyRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateAcademyHandler)(
            new UpdateAcademyCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me/tax-profile', name: 'api_v1_academy_me_tax_profile_update', methods: ['PUT'])]
    public function updateTaxProfile(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = UpdateAcademyTaxProfileRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateAcademyTaxProfileHandler)(
            new UpdateAcademyTaxProfileCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me/shield', name: 'api_v1_academy_me_shield_update', methods: ['POST'])]
    public function updateShield(Request $request, TenantContext $tenantContext): JsonResponse
    {
        /** @var ?UploadedFile $shieldFile */
        $shieldFile = $request->files->get('shield');

        if (null === $shieldFile) {
            throw new BadRequestHttpException('"shield" file is required.');
        }

        $view = ($this->uploadAcademyShieldHandler)(
            new UploadAcademyShieldCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
                $shieldFile,
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me/shield', name: 'api_v1_academy_me_shield_delete', methods: ['DELETE'])]
    public function deleteShield(TenantContext $tenantContext): Response
    {
        ($this->deleteAcademyShieldHandler)(
            new DeleteAcademyShieldCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
            )
        );

        return new Response(status: 204);
    }

    private function requireActorId(TenantContext $tenantContext): string
    {
        $actorId = $tenantContext->getUserId();

        if (null === $actorId || '' === $actorId) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $actorId;
    }
}
