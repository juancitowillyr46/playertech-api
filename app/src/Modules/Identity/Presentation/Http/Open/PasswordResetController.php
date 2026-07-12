<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Open;

use App\Modules\Identity\Application\Command\ConfirmPasswordResetCommand;
use App\Modules\Identity\Application\Command\RequestPasswordResetCommand;
use App\Modules\Identity\Application\Handler\ConfirmPasswordResetHandler;
use App\Modules\Identity\Application\Handler\RequestPasswordResetHandler;
use App\Modules\Identity\Presentation\Http\Request\ConfirmPasswordResetRequest;
use App\Modules\Identity\Presentation\Http\Request\RequestPasswordResetRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/public/users/password-reset')]
final class PasswordResetController extends AbstractApiController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly RequestPasswordResetHandler $requestPasswordResetHandler,
        private readonly ConfirmPasswordResetHandler $confirmPasswordResetHandler,
        private readonly string $publicUrl,
    ) {
    }

    #[Route('/request', name: 'api_v1_public_user_password_reset_request', methods: ['POST'])]
    public function request(Request $request): JsonResponse
    {
        $input = RequestPasswordResetRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        ($this->requestPasswordResetHandler)(new RequestPasswordResetCommand($input->toInput(), $this->publicUrl));

        return new JsonResponse([
            'data' => new \stdClass(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/confirm/{token}', name: 'api_v1_public_user_password_reset_confirm', methods: ['POST'])]
    public function confirm(string $token, Request $request): JsonResponse
    {
        $input = ConfirmPasswordResetRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->confirmPasswordResetHandler)(new ConfirmPasswordResetCommand($token, $input->toInput()));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }
}
