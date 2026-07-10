<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Response;

use App\Modules\Category\Domain\Category\Category;

final readonly class CategoryResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $categoryKey,
        private string $name,
        private int $minAge,
        private int $maxAge,
        private ?string $description,
        private string $status
    ) {
    }

    public static function fromCategory(Category $category): self
    {
        return new self(
            $category->id()->value(),
            $category->academyId()->value(),
            $category->categoryKey(),
            $category->name()->value(),
            $category->minAge()->value(),
            $category->maxAge()->value(),
            $category->description()?->value(),
            $category->status()->value()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'categoryKey' => $this->categoryKey,
            'name' => $this->name,
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
            'description' => $this->description,
            'status' => $this->status
        ];
    }
}
