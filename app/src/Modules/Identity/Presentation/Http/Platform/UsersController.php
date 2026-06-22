<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Platform;

use App\Modules\Identity\Application\Command\CreateUserCommand;
use App\Modules\Identity\Application\Command\DisableUserCommand;
use App\Modules\Identity\Application\Command\EnableUserCommand;
use App\Modules\Identity\Application\Command\UpdateUserCommand;
use App\Modules\Identity\Application\Dto\CreateUserInput;
use App\Modules\Identity\Application\Dto\UpdateUserInput;
use App\Modules\Identity\Application\Handler\CreateUserHandler;
use App\Modules\Identity\Application\Handler\DisableUserHandler;
use App\Modules\Identity\Application\Handler\EnableUserHandler;
use App\Modules\Identity\Application\Handler\ListUsersHandler;
use App\Modules\Identity\Application\Handler\ShowUserHandler;
use App\Modules\Identity\Application\Handler\UpdateUserHandler;
use App\Modules\Identity\Application\Query\ListUsersQuery;
use App\Modules\Identity\Application\Query\ShowUserQuery;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\Exception\ValidationException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/platform/users')]
final class UsersController
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

    #[Route('', name: 'api_v1_platform_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = array_map(
            static fn ($view): array => $view->toArray(),
            ($this->listUsersHandler)(new ListUsersQuery())
        );

        return new JsonResponse([
            'data' => $users,
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('', name: 'api_v1_platform_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateUserInput::fromArray($request->toArray());
        $this->assertValid($input);

        $view = ($this->createUserHandler)(
            new CreateUserCommand(
                $this->requireActorId(),
                $input
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/{userId}', name: 'api_v1_platform_users_show', methods: ['GET'])]
    public function show(string $userId): JsonResponse
    {
        $view = ($this->showUserHandler)(new ShowUserQuery($userId));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}', name: 'api_v1_platform_users_update', methods: ['PUT'])]
    public function update(string $userId, Request $request): JsonResponse
    {
        $input = UpdateUserInput::fromArray($request->toArray());
        $this->assertValid($input);

        $view = ($this->updateUserHandler)(
            new UpdateUserCommand(
                $this->requireActorId(),
                $userId,
                $input
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}/disable', name: 'api_v1_platform_users_disable', methods: ['POST'])]
    public function disable(string $userId): JsonResponse
    {
        $view = ($this->disableUserHandler)(
            new DisableUserCommand(
                $this->requireActorId(),
                $userId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{userId}/enable', name: 'api_v1_platform_users_enable', methods: ['POST'])]
    public function enable(string $userId): JsonResponse
    {
        $view = ($this->enableUserHandler)(
            new EnableUserCommand(
                $this->requireActorId(),
                $userId
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    private function requireActorId(): string
    {
        $user = $this->security->getUser();

        if (!$user instanceof AccountUser) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $user->getId();
    }

    private function assertValid(object $input): void
    {
        $violations = $this->validator->validate($input);

        if (0 === count($violations)) {
            return;
        }

        throw new ValidationException($violations);
    }
}
