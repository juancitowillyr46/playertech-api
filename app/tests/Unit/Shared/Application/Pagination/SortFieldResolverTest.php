<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Pagination;

use App\Shared\Application\Pagination\SortFieldResolver;
use PHPUnit\Framework\TestCase;

final class SortFieldResolverTest extends TestCase
{
    public function testItResolvesOnlyAllowedSortFields(): void
    {
        $resolver = new SortFieldResolver(
            [
                'created_at' => 'auditTrail.createdAt.value',
                'name' => 'name',
                'status' => 'status',
            ],
            'auditTrail.createdAt.value',
        );

        self::assertSame('auditTrail.createdAt.value', $resolver->resolve('created_at'));
        self::assertSame('name', $resolver->resolve(' NAME '));
        self::assertSame('status', $resolver->resolve('status'));
        self::assertSame('auditTrail.createdAt.value', $resolver->resolve('unknown-field'));
    }
}
