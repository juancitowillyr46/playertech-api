<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Response;

use App\Modules\Team\Domain\Team\Team;

final readonly class TeamResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $categoryId,
        private string $name,
        private string $status,
    ) {
    }

    public static function fromTeam(Team $team): self
    {
        return new self(
            $team->id()->value(),
            $team->academyId()->value(),
            $team->categoryId()->value(),
            $team->name()->value(),
            $team->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academy_id' => $this->academyId,
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
