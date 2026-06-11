<?php

namespace App\Modules\Identity\Infrastructure\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

final class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse([
            'type' => 'https://api.playertech/errors/authentication',
            'title' => 'Authentication Error',
            'status' => Response::HTTP_UNAUTHORIZED,
            'detail' => 'Credenciales inválidas o usuario inactivo.',
            'instance' => $request->getPathInfo(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
