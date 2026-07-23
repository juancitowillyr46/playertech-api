<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Response;

use App\Modules\Team\Domain\Team\Team;

final readonly class TeamListItemResponse
{
    private function __construct(
        private string $id,
        private string $categoryId,
        private string $categoryName,
        private string $name,
        private string $status,
    ) {
    }

    public static function fromTeam(Team $team, string $categoryName): self
    {
        return new self(
            $team->id()->value(),
            $team->categoryId()->value(),
            $categoryName,
            $team->name()->value(),
            $team->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
