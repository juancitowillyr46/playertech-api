<?php

namespace App\Modules\Identity\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

final class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private readonly JWTTokenManagerInterface $jwtManager)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $jwt = $this->jwtManager->create($token->getUser());

        return new JsonResponse([
            'data' => [
                'token' => $jwt,
            ],
            'meta' => new \stdClass(),
        ]);
    }
}
