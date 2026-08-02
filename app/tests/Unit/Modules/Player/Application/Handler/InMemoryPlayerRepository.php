<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Application\Pagination\PaginationQuery;

final class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var Player[] */
    public array $players = [];

    public function save(Player $player): void
    {
        $this->players[$player->id()->value()] = $player;
    }

    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->academyId()->equals($academyId) && $player->id()->equals($playerId)) {
                return $player;
            }
        }

        return null;
    }

    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->academyId()->equals($academyId) && $player->documentNumber() === $documentNumber) {
                return $player;
            }
        }

        return null;
    }

    public function findOneByEmail(AcademyId $academyId, string $email): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->academyId()->equals($academyId) && null !== $player->email() && mb_strtolower($player->email()) === mb_strtolower(trim($email))) {
                return $player;
            }
        }

        return null;
    }

    public function findOneByPhone(AcademyId $academyId, string $phone): ?Player
    {
        foreach ($this->players as $player) {
            $normalizedPhone = preg_replace('/\D+/', '', trim($phone));
            if (null !== $normalizedPhone && 10 === strlen($normalizedPhone) && str_starts_with($normalizedPhone, '3')) {
                $normalizedPhone = '57' . $normalizedPhone;
            }
            if ($player->academyId()->equals($academyId) && null !== $player->phone() && ltrim($player->phone(), '+') === $normalizedPhone) {
                return $player;
            }
        }

        return null;
    }

    public function findAvailableByGuardian(AcademyId $academyId, LegalGuardianId $guardianId, ?string $query = null): array
    {
        $items = array_values(array_filter(
            $this->players,
            function (Player $player) use ($academyId, $query): bool {
                if (!$player->academyId()->equals($academyId)) {
                    return false;
                }

                if (null !== $query && '' !== trim($query)) {
                    $needle = $this->normalizeSearchText($query);
                    $firstName = $this->normalizeSearchText($player->firstName());
                    $lastName = $this->normalizeSearchText($player->lastName());
                    $combined = $firstName . ' ' . $lastName;
                    $documentNumber = $this->normalizeSearchText($player->documentNumber());

                    if (!str_contains($firstName, $needle) && !str_contains($lastName, $needle) && !str_contains($combined, $needle) && !str_contains($documentNumber, $needle)) {
                        return false;
                    }
                }

                return true;
            }
        ));

        return $items;
    }

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $gender = null,
        ?string $categoryId = null,
        ?string $documentNumber = null,
        ?string $documentType = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $fullName = null,
        ?string $createdAtFrom = null,
        ?string $createdAtTo = null,
        ?string $birthDateFrom = null,
        ?string $birthDateTo = null,
    ): array {
        $items = array_values(array_filter(
            $this->players,
            function (Player $player) use ($academyId, $gender, $categoryId, $documentNumber, $documentType, $firstName, $lastName, $fullName, $createdAtFrom, $createdAtTo, $birthDateFrom, $birthDateTo): bool {
                if (!$player->academyId()->equals($academyId)) {
                    return false;
                }

                if (null !== $gender && '' !== trim($gender) && mb_strtolower(trim((string) $player->gender())) !== mb_strtolower(trim($gender))) {
                    return false;
                }

                if (null !== $categoryId && '' !== trim($categoryId) && (null === $player->categoryId() || $player->categoryId()?->value() !== trim($categoryId))) {
                    return false;
                }

                if (null !== $documentNumber && '' !== trim($documentNumber) && $this->normalizeSearchText($player->documentNumber()) !== $this->normalizeSearchText($documentNumber)) {
                    return false;
                }

                if (null !== $documentType && '' !== trim($documentType) && mb_strtoupper(trim($player->documentType())) !== mb_strtoupper(trim($documentType))) {
                    return false;
                }

                $playerFirstName = $this->normalizeSearchText($player->firstName());
                $playerLastName = $this->normalizeSearchText($player->lastName());

                if (null !== $firstName && '' !== trim($firstName) && !str_contains($playerFirstName, $this->normalizeSearchText($firstName))) {
                    return false;
                }

                if (null !== $lastName && '' !== trim($lastName) && !str_contains($playerLastName, $this->normalizeSearchText($lastName))) {
                    return false;
                }

                if (null !== $fullName && '' !== trim($fullName)) {
                    $needle = $this->normalizeSearchText($fullName);
                    $combined = $playerFirstName . ' ' . $playerLastName;

                    if (!str_contains($playerFirstName, $needle) && !str_contains($playerLastName, $needle) && !str_contains($combined, $needle)) {
                        return false;
                    }
                }

                if (null !== $createdAtFrom && '' !== trim($createdAtFrom) && $player->auditTrail()?->createdAt()->value() < new \DateTimeImmutable(trim($createdAtFrom))) {
                    return false;
                }

                if (null !== $createdAtTo && '' !== trim($createdAtTo) && $player->auditTrail()?->createdAt()->value() > (new \DateTimeImmutable(trim($createdAtTo)))->setTime(23, 59, 59)) {
                    return false;
                }

                if (null !== $birthDateFrom && '' !== trim($birthDateFrom) && $player->birthDate() < new \DateTimeImmutable(trim($birthDateFrom))) {
                    return false;
                }

                if (null !== $birthDateTo && '' !== trim($birthDateTo) && $player->birthDate() > (new \DateTimeImmutable(trim($birthDateTo)))->setTime(23, 59, 59)) {
                    return false;
                }

                return true;
            }
        ));

        return [
            'items' => array_slice($items, ($pagination->page - 1) * $pagination->perPage, $pagination->perPage),
            'total' => count($items),
        ];
    }

    private function normalizeSearchText(string $value): string
    {
        $trimmed = trim($value);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed);

        return mb_strtolower($normalized !== false ? $normalized : $trimmed);
    }
}
