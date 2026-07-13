<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Player\Application\Guardian\ListByPlayer\ListPlayerGuardiansHandler;
use App\Modules\Player\Application\Guardian\ListByPlayer\ListPlayerGuardiansQuery;
use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianCommand;
use App\Modules\Player\Application\Guardian\Associate\AssociateGuardianHandler;
use App\Modules\Player\Application\Guardian\ChangePrimary\ChangePrimaryGuardianCommand;
use App\Modules\Player\Application\Guardian\ChangePrimary\ChangePrimaryGuardianHandler;
use App\Modules\Player\Application\Guardian\RemoveAssociation\RemoveGuardianAssociationCommand;
use App\Modules\Player\Application\Guardian\RemoveAssociation\RemoveGuardianAssociationHandler;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Presentation\Http\Request\AssociateGuardianRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/players/{playerId}/guardians')]
final class PlayerGuardianController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly ListPlayerGuardiansHandler $listPlayerGuardiansHandler,
        private readonly AssociateGuardianHandler $associateGuardianHandler,
        private readonly ChangePrimaryGuardianHandler $changePrimaryGuardianHandler,
        private readonly RemoveGuardianAssociationHandler $removeGuardianAssociationHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_player_guardians_list', methods: ['GET'])]
    public function list(string $playerId): JsonResponse
    {
        $items = ($this->listPlayerGuardiansHandler)(
            new ListPlayerGuardiansQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $items),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('', name: 'api_v1_academy_player_guardians_associate', methods: ['POST'])]
    public function associate(Request $request, string $playerId): JsonResponse
    {
        $input = AssociateGuardianRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->associateGuardianHandler)(
            new AssociateGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/{guardianId}/primary', name: 'api_v1_academy_player_guardians_primary', methods: ['PATCH'])]
    public function changePrimary(string $playerId, string $guardianId): JsonResponse
    {
        $view = ($this->changePrimaryGuardianHandler)(
            new ChangePrimaryGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
                new LegalGuardianId($guardianId),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{guardianId}', name: 'api_v1_academy_player_guardians_remove', methods: ['DELETE'])]
    public function remove(string $playerId, string $guardianId): Response
    {
        ($this->removeGuardianAssociationHandler)(
            new RemoveGuardianAssociationCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
                new LegalGuardianId($guardianId),
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
