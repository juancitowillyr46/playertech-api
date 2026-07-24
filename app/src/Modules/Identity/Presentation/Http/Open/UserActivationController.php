<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Open;

use App\Modules\Identity\Application\Command\ActivateUserCommand;
use App\Modules\Identity\Application\Handler\ActivateUserHandler;
use App\Modules\Identity\Presentation\Http\Request\ActivateUserRequest;
use App\Shared\Domain\Exception\ValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/public/users')]
final readonly class UserActivationController
{
    public function __construct(
        private ValidatorInterface $validator,
        private ActivateUserHandler $activateUserHandler,
        private string $authFrontendUrl,
    ) {
    }

    #[Route('/activate/{token}', name: 'api_v1_public_user_activate_redirect', methods: ['GET'])]
    public function redirectToActivationPage(string $token): RedirectResponse
    {
        return new RedirectResponse(sprintf('%s/activate-account/%s', rtrim($this->authFrontendUrl, '/'), $token));
    }

    #[Route('/activate/{token}', name: 'api_v1_public_user_activate', methods: ['POST'])]
    public function activate(string $token, Request $request): JsonResponse
    {
        $input = ActivateUserRequest::fromArray($request->toArray());
        $this->assertValid($input);

        $result = ($this->activateUserHandler)(new ActivateUserCommand($token, $input->toInput()));

        return new JsonResponse([
            'data' => $result->toArray(),
            'meta' => new \stdClass(),
        ]);
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
