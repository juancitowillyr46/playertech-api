<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Response;

use App\Modules\Category\Domain\Category\OnboardingCategory;

final readonly class OnboardingCategoryResponse
{
    private function __construct(
        private string $id,
        private string $code,
        private string $name,
        private int $minAge,
        private int $maxAge,
        private ?string $description,
        private string $status,
    ) {
    }

    public static function fromOnboardingCategory(OnboardingCategory $category): self
    {
        return new self(
            $category->id(),
            $category->code(),
            $category->name()->value(),
            $category->minAge()->value(),
            $category->maxAge()->value(),
            $category->description()?->value(),
            $category->status(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }
}
