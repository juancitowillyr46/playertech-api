<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Platform;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/platform/academies')]
final class AcademyController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AcademyRepository $academyRepository,
        private readonly Security $security,
    ) {
    }

    #[Route('', name: 'api_v1_platform_academies_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $payload = $this->normalizePayload($request->toArray());
        $actorId = $this->requireActorId();

        if (null !== $this->academyRepository->findOneByContactEmail($payload['contact_email'])) {
            throw new ConflictHttpException('El correo de contacto ya existe.');
        }

        $academy = new Academy();
        $academy->setName($payload['name']);
        $academy->setContactEmail($payload['contact_email']);
        $academy->setPhone($payload['phone']);
        $academy->setAddress($payload['address']);
        $academy->setCity($payload['city']);
        $academy->setLogo($payload['logo']);
        $academy->setCreatedBy($actorId);

        $this->entityManager->persist($academy);
        $this->entityManager->flush();

        return new JsonResponse([
            'data' => $this->serialize($academy),
            'meta' => new \stdClass(),
        ], Response::HTTP_CREATED);
    }

    #[Route('', name: 'api_v1_platform_academies_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $academies = array_map(
            fn (Academy $academy): array => $this->serialize($academy),
            $this->academyRepository->findAllOrdered()
        );

        return new JsonResponse([
            'data' => $academies,
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}', name: 'api_v1_platform_academies_show', methods: ['GET'])]
    public function show(string $academyId): JsonResponse
    {
        $academy = $this->requireAcademy($academyId);

        return new JsonResponse([
            'data' => $this->serialize($academy),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}', name: 'api_v1_platform_academies_update', methods: ['PUT'])]
    public function update(string $academyId, Request $request): JsonResponse
    {
        $academy = $this->requireAcademy($academyId);
        $payload = $this->normalizePayload($request->toArray());

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
            $this->requireActorId(),
        );

        $this->entityManager->flush();

        return new JsonResponse([
            'data' => $this->serialize($academy),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}/suspend', name: 'api_v1_platform_academies_suspend', methods: ['POST'])]
    public function suspend(string $academyId): JsonResponse
    {
        $academy = $this->requireAcademy($academyId);

        if (!$academy->isSuspended()) {
            $academy->suspend($this->requireActorId());
            $this->entityManager->flush();
        }

        return new JsonResponse([
            'data' => $this->serialize($academy),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{academyId}/reactivate', name: 'api_v1_platform_academies_reactivate', methods: ['POST'])]
    public function reactivate(string $academyId): JsonResponse
    {
        $academy = $this->requireAcademy($academyId);

        if (!$academy->isActive()) {
            $academy->reactivate($this->requireActorId());
            $this->entityManager->flush();
        }

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

        $academy = $this->entityManager->getRepository(Academy::class)->find($academyId);

        if (!$academy instanceof Academy) {
            throw new NotFoundHttpException('Academia no encontrada.');
        }

        return $academy;
    }

    private function requireActorId(): string
    {
        $user = $this->security->getUser();

        if (!$user instanceof AccountUser) {
            throw new BadRequestHttpException('No se pudo resolver el usuario autenticado.');
        }

        return $user->getId();
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
