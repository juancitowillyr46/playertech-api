<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\ListByPlayer;

use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;

final readonly class ListPlayerGuardiansHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
    ) {
    }

    /**
     * @return PlayerGuardianListItemResponse[]
     */
    public function __invoke(ListPlayerGuardiansQuery $query): array
    {
        if (null === $this->playerRepository->findById($query->academyId, $query->playerId)) {
            throw new PlayerNotFoundException();
        }

        $relations = $this->playerGuardianRepository->findAllByPlayer($query->academyId, $query->playerId);

        return array_values(array_map(
            function ($relation): PlayerGuardianListItemResponse {
                $guardian = $this->guardianRepository->findById($relation->academyId(), $relation->guardianId());

                if (null === $guardian) {
                    throw new \LogicException('Related guardian not found.');
                }

                return PlayerGuardianListItemResponse::fromPlayerGuardian(
                    $relation,
                    LegalGuardianResponse::fromLegalGuardian($guardian)
                );
            },
            $relations
        ));
    }
}
