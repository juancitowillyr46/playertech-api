<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http;

use App\Shared\Domain\Exception\ValidationException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController
{
    protected function assertValid(ValidatorInterface $validator, object $input): void
    {
        $violations = $validator->validate($input);

        if (0 === count($violations)) {
            return;
        }

        throw new ValidationException($violations);
    }

    protected function requireAuthenticatedUserId(Security $security): string
    {
        $user = $security->getUser();

        if (!is_object($user) || !method_exists($user, 'getId')) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        $userId = $user->getId();

        if (!is_string($userId) || '' === $userId) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $userId;
    }
}
