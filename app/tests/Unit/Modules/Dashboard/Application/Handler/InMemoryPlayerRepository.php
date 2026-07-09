<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Dashboard\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
final class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var Player[] */
    public array $items = [];
    public function save(Player $player): void { $this->items[$player->id()->value()] = $player; }
    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player { return $this->items[$playerId->value()] ?? null; }
    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player { return null; }
    public function findAllByAcademy(AcademyId $academyId): array { return array_values(array_filter($this->items, static fn (Player $player): bool => $player->academyId()->equals($academyId))); }
}
