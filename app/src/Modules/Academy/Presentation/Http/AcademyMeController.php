<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class AcademyMeController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AcademyRepository $academyRepository,
    ) {
    }

    #[Route('/academy/me', name: 'api_v1_academy_me', methods: ['GET'])]
    public function __invoke(TenantContext $tenantContext): JsonResponse
    {
        $academyId = $tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => [
                'mode' => $tenantContext->getMode(),
                'user_id' => $tenantContext->getUserId(),
                'academy_id' => $academyId,
                'role' => $tenantContext->getRole(),
                'roles' => $tenantContext->getRoles(),
            ],
            'meta' => new \stdClass(),
        ], Response::HTTP_OK);
    }

    #[Route('/academy/me', name: 'api_v1_academy_me_update', methods: ['PUT'])]
    public function update(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $academyId = $tenantContext->requireAcademyId();
        $academy = $this->requireAcademy($academyId);
        $payload = $this->normalizePayload($request->toArray());
        $actorId = $this->requireActorId($tenantContext);

        $duplicate = $this->academyRepository->findOneByContactEmail($payload['contact_email']);
        if (null !== $duplicate && $duplicate->getId() !== $academy->getId()) {
            throw new ConflictHttpException('El correo de contacto ya existe.');
        }

        $academy->updateProfile(
            $payload['name'],
            $payload['contact_email'],
            $payload['phone'],
            $payload['address'],
            $payload['city'],
            $payload['logo'],
            $actorId,
        );

        $this->entityManager->flush();

        return new JsonResponse([
            'data' => $this->serialize($academy),
            'meta' => new \stdClass(),
        ]);
    }

    private function requireAcademy(string $academyId): Academy
    {
        if (!Uuid::isValid($academyId)) {
            throw new BadRequestHttpException('Identificador de academia inválido.');
        }

        $academy = $this->academyRepository->find($academyId);

        if (!$academy instanceof Academy) {
            throw new NotFoundHttpException('Academia no encontrada.');
        }

        return $academy;
    }

    private function requireActorId(TenantContext $tenantContext): string
    {
        $actorId = $tenantContext->getUserId();

        if (null === $actorId || '' === $actorId) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $actorId;
    }

    private function normalizePayload(array $payload): array
    {
        $name = $this->requiredString($payload, 'name', 150);
        $contactEmail = $this->requiredEmail($payload, 'contact_email', 180);

        return [
            'name' => $name,
            'contact_email' => $contactEmail,
            'phone' => $this->optionalString($payload, 'phone', 30),
            'address' => $this->optionalString($payload, 'address', 255),
            'city' => $this->optionalString($payload, 'city', 120),
            'logo' => $this->optionalString($payload, 'logo', 255),
        ];
    }

    private function requiredString(array $payload, string $key, int $maxLength): string
    {
        if (!array_key_exists($key, $payload)) {
            throw new BadRequestHttpException(sprintf('El campo "%s" es obligatorio.', $key));
        }

        $value = trim((string) $payload[$key]);

        if ('' === $value) {
            throw new BadRequestHttpException(sprintf('El campo "%s" es obligatorio.', $key));
        }

        if (mb_strlen($value) > $maxLength) {
            throw new BadRequestHttpException(sprintf('El campo "%s" excede la longitud máxima permitida.', $key));
        }

        return $value;
    }

    private function requiredEmail(array $payload, string $key, int $maxLength): string
    {
        $value = $this->requiredString($payload, $key, $maxLength);

        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException(sprintf('El campo "%s" debe ser un correo válido.', $key));
        }

        return mb_strtolower($value);
    }

    private function optionalString(array $payload, string $key, int $maxLength): ?string
    {
        if (!array_key_exists($key, $payload) || null === $payload[$key]) {
            return null;
        }

        $value = trim((string) $payload[$key]);

        if ('' === $value) {
            return null;
        }

        if (mb_strlen($value) > $maxLength) {
            throw new BadRequestHttpException(sprintf('El campo "%s" excede la longitud máxima permitida.', $key));
        }

        return $value;
    }

    private function serialize(Academy $academy): array
    {
        return [
            'id' => $academy->getId(),
            'name' => $academy->getName(),
            'contact_email' => $academy->getContactEmail(),
            'phone' => $academy->getPhone(),
            'address' => $academy->getAddress(),
            'city' => $academy->getCity(),
            'logo' => $academy->getLogo(),
            'status' => $academy->getStatus(),
            'created_at' => $academy->getCreatedAt()->format(DATE_ATOM),
            'created_by' => $academy->getCreatedBy(),
            'updated_at' => $academy->getUpdatedAt()?->format(DATE_ATOM),
            'updated_by' => $academy->getUpdatedBy(),
        ];
    }
}
