<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Query\ListGuardianOptionsQuery;
use App\Modules\Guardian\Application\Response\GuardianOptionResponse;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListGuardianOptionsHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    /**
     * @return GuardianOptionResponse[]
     */
    public function __invoke(ListGuardianOptionsQuery $query): array
    {
        $criteria = trim((string) $query->query);
        $guardians = $this->guardianRepository->findAllByAcademy(
            $query->academyId,
            new PaginationQuery(1, 20, 'first_name', 'ASC'),
            null,
            null,
            null,
            null,
            null,
        );

        if ('' !== $criteria) {
            $needle = $this->normalizeSearchText($criteria);
            $guardians['items'] = array_values(array_filter(
                $guardians['items'],
                function ($guardian) use ($needle): bool {
                    $firstName = $this->normalizeSearchText($guardian->firstName());
                    $lastName = $this->normalizeSearchText($guardian->lastName());
                    $fullName = $firstName.' '.$lastName;

                    return str_contains($firstName, $needle)
                        || str_contains($lastName, $needle)
                        || str_contains($fullName, $needle);
                }
            ));
        }

        return array_values(array_map(
            static fn ($guardian): GuardianOptionResponse => GuardianOptionResponse::fromGuardian($guardian),
            $guardians['items']
        ));
    }

    private function normalizeSearchText(string $value): string
    {
        $trimmed = trim($value);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed);

        return mb_strtolower($normalized !== false ? $normalized : $trimmed);
    }
}
