<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Application\Command\UpdateOwnNameCommand;
use App\Modules\Identity\Application\Handler\UpdateOwnNameHandler;
use App\Modules\Identity\Presentation\Http\Request\UpdateOwnNameRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProfileController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly UpdateOwnNameHandler $updateOwnNameHandler,
    ) {
    }

    #[Route('/auth/me/name', name: 'api_v1_auth_me_name_update', methods: ['PUT'])]
    public function updateName(Request $request): JsonResponse
    {
        $input = UpdateOwnNameRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateOwnNameHandler)(
            new UpdateOwnNameCommand(
                $this->requireAuthenticatedUserId($this->security),
                $input->toInput()
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }
}
