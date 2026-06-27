<?php

declare(strict_types=1);

namespace App\Modules\Category\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Category\Domain\Category\CategoryId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;

final class CategoryIdType extends AbstractUuidType
{
    public const NAME = 'category_id';

    protected function getValueObjectClass(): string
    {
        return CategoryId::class;
    }
}