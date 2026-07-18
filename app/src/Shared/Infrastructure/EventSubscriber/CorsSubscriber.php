<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class CorsSubscriber implements EventSubscriberInterface
{
    /**
     * @param string[] $allowedOrigins
     */
    public function __construct(
        private array $allowedOrigins = [
            'http://localhost:4200',
            'http://127.0.0.1:4200',
        ],
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onRequest', 100],
            KernelEvents::RESPONSE => ['onResponse', 0],
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (strtoupper($request->getMethod()) !== 'OPTIONS') {
            return;
        }

        $origin = $request->headers->get('Origin');

        if (null === $origin || !$this->isAllowedOrigin($origin)) {
            return;
        }

        $response = new Response('', Response::HTTP_NO_CONTENT);
        $this->addCorsHeaders($response, $origin);
        $event->setResponse($response);
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $origin = $request->headers->get('Origin');

        if (null === $origin || !$this->isAllowedOrigin($origin)) {
            return;
        }

        $this->addCorsHeaders($event->getResponse(), $origin);
    }

    private function isAllowedOrigin(string $origin): bool
    {
        return in_array($origin, $this->allowedOrigins, true);
    }

    private function addCorsHeaders(Response $response, string $origin): void
    {
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Vary', 'Origin', false);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Max-Age', '86400');
    }
}
