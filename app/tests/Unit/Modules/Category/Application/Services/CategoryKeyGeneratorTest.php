<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Application\Services;

use App\Modules\Category\Application\Services\CategoryKeyGenerator;
use PHPUnit\Framework\TestCase;

final class CategoryKeyGeneratorTest extends TestCase
{
    public function testItGeneratesStableKeysFromCategoryNames(): void
    {
        $generator = new CategoryKeyGenerator();

        self::assertSame('SUB-12', $generator->generate('Sub 12'));
        self::assertSame('SUB-14-A', $generator->generate('Sub 14 A'));
        self::assertSame('CATEGORIA-ESPECIAL', $generator->generate('Categoría especial'));
    }
}
