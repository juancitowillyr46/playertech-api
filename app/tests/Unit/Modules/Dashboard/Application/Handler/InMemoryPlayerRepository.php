<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Dashboard\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Application\Pagination\PaginationQuery;
final class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var Player[] */
    public array $items = [];
    public function save(Player $player): void { $this->items[$player->id()->value()] = $player; }
    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player { return $this->items[$playerId->value()] ?? null; }
    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player { return null; }
    public function findOneByEmail(AcademyId $academyId, string $email): ?Player { return null; }
    public function findOneByPhone(AcademyId $academyId, string $phone): ?Player { return null; }
    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $gender = null,
        ?string $categoryId = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $fullName = null,
        ?string $createdAtFrom = null,
        ?string $createdAtTo = null,
        ?string $birthDateFrom = null,
        ?string $birthDateTo = null,
    ): array {
        $items = array_values(array_filter($this->items, static fn (Player $player): bool => $player->academyId()->equals($academyId)));

        return [
            'items' => array_slice($items, ($pagination->page - 1) * $pagination->perPage, $pagination->perPage),
            'total' => count($items),
        ];
    }
}
