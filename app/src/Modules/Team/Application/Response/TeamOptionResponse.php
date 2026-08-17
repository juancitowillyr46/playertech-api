<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Response;

use App\Modules\Team\Domain\Team\Team;

final readonly class TeamOptionResponse
{
    private function __construct(
        private string $id,
        private string $name,
        private string $categoryName,
        private string $status,
    ) {
    }

    public static function fromTeam(Team $team, string $categoryName): self
    {
        return new self(
            $team->id()->value(),
            $team->name()->value(),
            $categoryName,
            $team->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoryName' => $this->categoryName,
            'status' => $this->status,
        ];
    }
}
