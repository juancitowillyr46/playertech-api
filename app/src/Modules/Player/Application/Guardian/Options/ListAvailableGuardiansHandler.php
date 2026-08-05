<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\Options;

use App\Modules\Guardian\Application\Response\GuardianOptionResponse;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;

final readonly class ListAvailableGuardiansHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
    ) {
    }

    /**
     * @return GuardianOptionResponse[]
     */
    public function __invoke(ListAvailableGuardiansQuery $query): array
    {
        if (null === $this->playerRepository->findById($query->academyId, $query->playerId)) {
            throw new PlayerNotFoundException();
        }

        $assignedGuardianIds = array_map(
            static fn ($relation): string => $relation->guardianId()->value(),
            $this->playerGuardianRepository->findAllByPlayer($query->academyId, $query->playerId)
        );

        $guardians = $this->guardianRepository->findAllByAcademy(
            $query->academyId,
            new \App\Shared\Application\Pagination\PaginationQuery(1, 500, 'first_name', 'ASC'),
            null,
            null,
            null,
            null,
            null,
        )['items'];

        $criteria = trim((string) $query->query);
        $guardians = array_values(array_filter($guardians, function ($guardian) use ($criteria, $assignedGuardianIds): bool {
            if (in_array($guardian->id()->value(), $assignedGuardianIds, true)) {
                return false;
            }

            if ('' === $criteria) {
                return true;
            }

            $needle = $this->normalizeSearchText($criteria);
            $firstName = $this->normalizeSearchText($guardian->firstName());
            $lastName = $this->normalizeSearchText($guardian->lastName());
            $fullName = $firstName.' '.$lastName;

            return str_contains($firstName, $needle)
                || str_contains($lastName, $needle)
                || str_contains($fullName, $needle)
                || (null !== $guardian->documentNumber() && str_contains($this->normalizeSearchText($guardian->documentNumber()), $needle));
        }));

        return array_values(array_map(
            static fn ($guardian): GuardianOptionResponse => GuardianOptionResponse::fromGuardian($guardian),
            $guardians
        ));
    }

    private function normalizeSearchText(string $value): string
    {
        $trimmed = trim($value);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed);

        return mb_strtolower($normalized !== false ? $normalized : $trimmed);
    }
}
