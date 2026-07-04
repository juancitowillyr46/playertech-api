<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http;

use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Dto\UpdateAcademyInput;
use App\Modules\Academy\Application\Handler\GetAcademyContextHandler;
use App\Modules\Academy\Application\Handler\UpdateAcademyHandler;
use App\Modules\Academy\Application\Query\GetAcademyContextQuery;
use App\Modules\Academy\Application\Shield\Upload\UploadAcademyShieldCommand;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AcademyMeController extends AbstractApiController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly ValidatorInterface $validator,
        private readonly GetAcademyContextHandler $getAcademyContextHandler,
        private readonly UpdateAcademyHandler $updateAcademyHandler
    ) {
    }

    #[Route('/academy/me', name: 'api_v1_academy_me', methods: ['GET'])]
    public function getAcademyContext(): JsonResponse
    {
        $view = ($this->getAcademyContextHandler)(new GetAcademyContextQuery());

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/academy/me', name: 'api_v1_academy_me_update', methods: ['PUT'])]
    public function update(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = UpdateAcademyInput::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateAcademyHandler)(
            new UpdateAcademyCommand(
                $this->requireActorId($tenantContext),
                $tenantContext->requireAcademyId(),
                $input
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

        $this->commandBus->dispatch(
            new UploadAcademyShieldCommand(
                $tenantContext->requireAcademyId(),
                $shieldFile,
                $this->requireActorId($tenantContext)
            )
        );

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function requireActorId(TenantContext $tenantContext): string
    {
        $actorId = $tenantContext->getUserId();

        if (null === $actorId || '' === $actorId) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $actorId;
    }
}
