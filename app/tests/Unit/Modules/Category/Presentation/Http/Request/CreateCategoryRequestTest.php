<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Presentation\Http\Request;

use App\Modules\Category\Presentation\Http\Request\CreateCategoryRequest;
use PHPUnit\Framework\TestCase;

final class CreateCategoryRequestTest extends TestCase
{
    public function testItBuildsThePayloadWithCamelCaseKeys(): void
    {
        $request = CreateCategoryRequest::fromArray([
            'name' => 'Sub 12',
            'minAge' => 11,
            'maxAge' => 12,
            'description' => 'Categoria formativa',
        ]);

        $input = $request->toInput();

        self::assertSame('Sub 12', $input->name);
        self::assertSame(11, $input->minAge);
        self::assertSame(12, $input->maxAge);
        self::assertSame('Categoria formativa', $input->description);
    }
}
