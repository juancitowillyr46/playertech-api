<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Presentation\Http\Request;

use App\Modules\Category\Presentation\Http\Request\UpdateCategoryRequest;
use PHPUnit\Framework\TestCase;

final class UpdateCategoryRequestTest extends TestCase
{
    public function testItBuildsThePayloadWithoutCategoryKey(): void
    {
        $request = UpdateCategoryRequest::fromArray([
            'name' => 'Sub 14',
            'min_age' => 13,
            'max_age' => 14,
            'description' => 'Categoria formativa',
        ]);

        $input = $request->toInput();

        self::assertSame('Sub 14', $input->name);
        self::assertSame(13, $input->minAge);
        self::assertSame(14, $input->maxAge);
        self::assertSame('Categoria formativa', $input->description);
    }
}
