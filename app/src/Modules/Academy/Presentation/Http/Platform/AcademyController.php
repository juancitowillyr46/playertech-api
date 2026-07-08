<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Platform;

use App\Modules\Academy\Application\Command\CreateAcademyCommand;
use App\Modules\Academy\Application\Command\ReactivateAcademyCommand;
use App\Modules\Academy\Application\Command\SuspendAcademyCommand;
use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Handler\CreateAcademyHandler;
use App\Modules\Academy\Application\Handler\ListAcademiesHandler;
use App\Modules\Academy\Application\Handler\ReactivateAcademyHandler;
use App\Modules\Academy\Application\Handler\ShowAcademyHandler;
use App\Modules\Academy\Application\Handler\SuspendAcademyHandler;
use App\Modules\Academy\Application\Handler\UpdateAcademyHandler;
use App\Modules\Academy\Application\Query\ListAcademiesQuery;
use App\Modules\Academy\Application\Query\ShowAcademyQuery;
use App\Modules\Academy\Presentation\Http\Request\CreateAcademyRequest;
use App\Modules\Academy\Presentation\Http\Request\UpdateAcademyRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/platform/academies')]
final class AcademyController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateAcademyHandler $createAcademyHandler,
        private readonly ListAcademiesHandler $listAcademiesHandler,
        private readonly ShowAcademyHandler $showAcademyHandler,
        private readonly UpdateAcademyHandler $updateAcademyHandler,
        private readonly SuspendAcademyHandler $suspendAcademyHandler,
        private readonly ReactivateAcademyHandler $reactivateAcademyHandler,
    ) {
    }

    #[Route('', name: 'api_v1_platform_academies_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateAcademyRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createAcademyHandler)(
            new CreateAcademyCommand(
                $this->requireActorId(),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('', name: 'api_v1_platform_academies_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $academies = array_map(
            static fn ($view): array => $view->toArray(),
            ($this->listAcademiesHandler)(new ListAcademiesQuery())
        );

        return new JsonResponse([
            'data' => $academies,
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}', name: 'api_v1_platform_academies_show', methods: ['GET'])]
    public function show(string $academyId): JsonResponse
    {
        $view = ($this->showAcademyHandler)(new ShowAcademyQuery($academyId));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}', name: 'api_v1_platform_academies_update', methods: ['PUT'])]
    public function update(string $academyId, Request $request): JsonResponse
    {
        $input = UpdateAcademyRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateAcademyHandler)(
            new UpdateAcademyCommand(
                $this->requireActorId(),
                $academyId,
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}/suspend', name: 'api_v1_platform_academies_suspend', methods: ['POST'])]
    public function suspend(string $academyId): JsonResponse
    {
        $view = ($this->suspendAcademyHandler)(
            new SuspendAcademyCommand(
                $this->requireActorId(),
                $academyId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}/reactivate', name: 'api_v1_platform_academies_reactivate', methods: ['POST'])]
    public function reactivate(string $academyId): JsonResponse
    {
        $view = ($this->reactivateAcademyHandler)(
            new ReactivateAcademyCommand(
                $this->requireActorId(),
                $academyId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    private function requireActorId(): string
    {
        return $this->requireAuthenticatedUserId($this->security);
    }
}
