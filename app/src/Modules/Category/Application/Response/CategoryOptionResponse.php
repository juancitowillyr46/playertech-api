<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Response;

use App\Modules\Category\Domain\Category\Category;

final readonly class CategoryOptionResponse
{
    private function __construct(
        private string $id,
        private string $categoryKey,
        private string $name,
        private string $status,
    ) {
    }

    public static function fromCategory(Category $category): self
    {
        return new self(
            $category->id()->value(),
            $category->categoryKey(),
            $category->name()->value(),
            $category->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryKey' => $this->categoryKey,
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
