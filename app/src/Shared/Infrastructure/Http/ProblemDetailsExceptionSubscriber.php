<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Exception\BusinessRuleException;
use App\Shared\Domain\Exception\ConflictException;
use App\Shared\Domain\Exception\ForbiddenException;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Exception\UnauthorizedException;
use App\Shared\Domain\Exception\ValidationException;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class ProblemDetailsExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $instance = $event->getRequest()->getPathInfo();

        $this->logException($throwable, $instance);

        $problem = $this->toProblemDetails(
            $throwable,
            $instance
        );

        if (null === $problem) {
            return;
        }

        $event->setResponse(
            new JsonResponse(
                $problem['body'],
                $problem['status']
            )
        );
    }

    private function toProblemDetails(
        Throwable $throwable,
        string $instance
    ): ?array {
        if ($throwable instanceof ConflictException) {
            return $this->problem(
                Response::HTTP_CONFLICT,
                'https://api.playertech/errors/conflict',
                'Conflict',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof ForbiddenException) {
            return $this->problem(
                Response::HTTP_FORBIDDEN,
                'https://api.playertech/errors/forbidden',
                'Forbidden',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof NotFoundException) {
            return $this->problem(
                Response::HTTP_NOT_FOUND,
                'https://api.playertech/errors/not-found',
                'Not Found',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof UnauthorizedException) {
            return $this->problem(
                Response::HTTP_UNAUTHORIZED,
                'https://api.playertech/errors/unauthorized',
                'Unauthorized',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof ValidationException) {
            return $this->problem(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'https://api.playertech/errors/validation',
                'Validation Error',
                $throwable->getMessage(),
                $instance,
                [
                    'violations' => array_map(
                        static fn ($violation): array => [
                            'property' => $violation->getPropertyPath(),
                            'message' => $violation->getMessage(),
                        ],
                        iterator_to_array($throwable->violations())
                    ),
                ]
            );
        }

        if ($throwable instanceof BusinessRuleException) {
            return $this->problem(
                Response::HTTP_BAD_REQUEST,
                'https://api.playertech/errors/business-rule',
                'Business Rule Error',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof DomainException) {
            return $this->problem(
                Response::HTTP_BAD_REQUEST,
                'https://api.playertech/errors/domain',
                'Domain Error',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof AccessDeniedHttpException) {
            return $this->problem(
                Response::HTTP_FORBIDDEN,
                'https://api.playertech/errors/forbidden',
                'Forbidden',
                $throwable->getMessage(),
                $instance,
            );
        }

        if ($throwable instanceof NotFoundHttpException) {
            return $this->problem(
                Response::HTTP_NOT_FOUND,
                'https://api.playertech/errors/not-found',
                'Not Found',
                $throwable->getMessage(),
                $instance,
            );
        }

        return $this->problem(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'https://api.playertech/errors/internal-server-error',
            'Internal Server Error',
            //$throwable->getMessage(),
            'An unexpected error occurred. Please contact support if the problem persists.',
            $instance,
        );
    }

    private function logException(
        Throwable $throwable,
        string $path,
    ): void {
        
        if ($throwable instanceof ValidationException) {
            $this->logger->warning(
                $throwable->getMessage(),
                [
                    'path' => $path,
                ]
            );

            return;
        }

        if ($throwable instanceof DomainException) {
            $this->logger->notice(
                $throwable->getMessage(),
                [
                    'path' => $path,
                ]
            );

            return;
        }

        

        $this->logger->error(
            $throwable->getMessage(),
            [
                'exception' => $throwable,
                'path' => $path,
            ]
        );
    }

    private function problem(
        int $status,
        string $type,
        string $title,
        string $detail,
        string $instance,
        array $extra = [],
    ): array {
        return [
            'status' => $status,
            'body' => array_merge(
                [
                    'type' => $type,
                    'title' => $title,
                    'status' => $status,
                    'detail' => $detail,
                    'instance' => $instance,
                ],
                $extra
            ),
        ];
    }
}
