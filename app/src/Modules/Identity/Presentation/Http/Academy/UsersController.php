<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Academy;

use App\Modules\Identity\Application\Command\CreateUserCommand;
use App\Modules\Identity\Application\Command\DisableUserCommand;
use App\Modules\Identity\Application\Command\EnableUserCommand;
use App\Modules\Identity\Application\Command\UpdateUserCommand;
use App\Modules\Identity\Application\Handler\CreateUserHandler;
use App\Modules\Identity\Application\Handler\DisableUserHandler;
use App\Modules\Identity\Application\Handler\EnableUserHandler;
use App\Modules\Identity\Application\Handler\ListUsersHandler;
use App\Modules\Identity\Application\Handler\ShowUserHandler;
use App\Modules\Identity\Application\Handler\UpdateUserHandler;
use App\Modules\Identity\Application\Query\ListUsersQuery;
use App\Modules\Identity\Application\Query\ShowUserQuery;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Identity\Presentation\Http\Request\CreateUserRequest;
use App\Modules\Identity\Presentation\Http\Request\UpdateUserRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/users')]
final class UsersController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateUserHandler $createUserHandler,
        private readonly ListUsersHandler $listUsersHandler,
        private readonly ShowUserHandler $showUserHandler,
        private readonly UpdateUserHandler $updateUserHandler,
        private readonly DisableUserHandler $disableUserHandler,
        private readonly EnableUserHandler $enableUserHandler,
    ) {
    }

    #[Route('', name: 'api_v1_academy_users_list', methods: ['GET'])]
    public function list(TenantContext $tenantContext): JsonResponse
    {
        $academyId = $tenantContext->requireAcademyId();
        $users = array_map(
            static fn ($view): array => $view->toArray(),
            ($this->listUsersHandler)(new ListUsersQuery($academyId))
        );

        return new JsonResponse([
            'data' => $users,
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('', name: 'api_v1_academy_users_create', methods: ['POST'])]
    public function create(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = CreateUserRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createUserHandler)(
            new CreateUserCommand(
                $this->requireActorId($tenantContext),
                $input->toInput(),
                $tenantContext->requireAcademyId()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/{userId}', name: 'api_v1_academy_users_show', methods: ['GET'])]
    public function show(string $userId, TenantContext $tenantContext): JsonResponse
    {
        $view = ($this->showUserHandler)(new ShowUserQuery($userId, $tenantContext->requireAcademyId()));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}', name: 'api_v1_academy_users_update', methods: ['PUT'])]
    public function update(string $userId, Request $request, TenantContext $tenantContext): JsonResponse
    {
        $input = UpdateUserRequest::fromArray($request->toArray());
        $this->assertValid($input);

        $view = ($this->updateUserHandler)(
            new UpdateUserCommand(
                $this->requireActorId($tenantContext),
                $userId,
                $input->toInput(),
                $tenantContext->requireAcademyId()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}/disable', name: 'api_v1_academy_users_disable', methods: ['POST'])]
    public function disable(string $userId, TenantContext $tenantContext): JsonResponse
    {
        $view = ($this->disableUserHandler)(
            new DisableUserCommand(
                $this->requireActorId($tenantContext),
                $userId,
                $tenantContext->requireAcademyId()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}/enable', name: 'api_v1_academy_users_enable', methods: ['POST'])]
    public function enable(string $userId, TenantContext $tenantContext): JsonResponse
    {
        $view = ($this->enableUserHandler)(
            new EnableUserCommand(
                $this->requireActorId($tenantContext),
                $userId,
                $tenantContext->requireAcademyId()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
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
