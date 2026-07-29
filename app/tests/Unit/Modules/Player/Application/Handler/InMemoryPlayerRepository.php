<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
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

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $gender = null,
        ?string $categoryId = null,
        ?string $createdAtFrom = null,
        ?string $createdAtTo = null,
        ?string $birthDateFrom = null,
        ?string $birthDateTo = null,
    ): array {
        $items = array_values(array_filter(
            $this->players,
            static function (Player $player) use ($academyId, $gender, $categoryId, $createdAtFrom, $createdAtTo, $birthDateFrom, $birthDateTo): bool {
                if (!$player->academyId()->equals($academyId)) {
                    return false;
                }

                if (null !== $gender && '' !== trim($gender) && mb_strtolower(trim((string) $player->gender())) !== mb_strtolower(trim($gender))) {
                    return false;
                }

                if (null !== $categoryId && '' !== trim($categoryId) && (null === $player->categoryId() || $player->categoryId()?->value() !== trim($categoryId))) {
                    return false;
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
}
