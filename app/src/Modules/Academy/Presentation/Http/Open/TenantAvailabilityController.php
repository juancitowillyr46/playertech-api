<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Open;

use App\Modules\Academy\Application\Response\PublicAvailabilityResponse;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/public/tenants')]
final readonly class TenantAvailabilityController
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    #[Route('/availability', name: 'api_v1_public_tenant_availability', methods: ['GET'])]
    public function availability(Request $request): JsonResponse
    {
        $contactEmail = trim((string) $request->query->get('contactEmail', ''));
        $phone = trim((string) $request->query->get('phone', ''));

        $emailAvailable = '' === $contactEmail || null === $this->academyRepository->findOneByContactEmail(new Email($contactEmail));
        $phoneAvailable = '' === $phone || null === $this->academyRepository->findOneByPhone(new PhoneNumber($phone));

        $view = new PublicAvailabilityResponse($emailAvailable, $phoneAvailable);

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }
}
