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

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $items = array_values(array_filter(
            $this->players,
            static fn (Player $player): bool => $player->academyId()->equals($academyId)
        ));

        return [
            'items' => array_slice($items, ($pagination->page - 1) * $pagination->perPage, $pagination->perPage),
            'total' => count($items),
        ];
    }
}
