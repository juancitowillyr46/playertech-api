<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Shared\Domain\Exception\ValidationException;
use DomainException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class ProblemDetailsExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $problem = $this->toProblemDetails($throwable, $event->getRequest()->getPathInfo());

        if (null === $problem) {
            return;
        }

        $event->setResponse(new JsonResponse($problem['body'], $problem['status']));
    }

    private function toProblemDetails(Throwable $throwable, string $instance): ?array
    {
        if ($throwable instanceof AcademyAlreadyExistsException) {
            return [
                'status' => Response::HTTP_CONFLICT,
                'body' => [
                    'type' => 'https://api.playertech/errors/conflict',
                    'title' => 'Conflict',
                    'status' => Response::HTTP_CONFLICT,
                    'detail' => $throwable->getMessage(),
                    'instance' => $instance,
                ],
            ];
        }

        if ($throwable instanceof ValidationException) {
            return [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'body' => [
                    'type' => 'https://api.playertech/errors/validation',
                    'title' => 'Validation Error',
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'detail' => $throwable->getMessage(),
                    'instance' => $instance,
                    'violations' => array_map(static fn ($violation): array => [
                        'property' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage(),
                    ], iterator_to_array($throwable->violations())),
                ],
            ];
        }

        if ($throwable instanceof DomainException) {
            return [
                'status' => Response::HTTP_BAD_REQUEST,
                'body' => [
                    'type' => 'https://api.playertech/errors/domain',
                    'title' => 'Domain Error',
                    'status' => Response::HTTP_BAD_REQUEST,
                    'detail' => $throwable->getMessage(),
                    'instance' => $instance,
                ],
            ];
        }

        return [
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'body' => [
                'type' => 'https://api.playertech/errors/internal-server-error',
                'title' => 'Internal Server Error',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'detail' => $throwable->getMessage(),
                'instance' => $instance,
            ],
        ];
    }
}
