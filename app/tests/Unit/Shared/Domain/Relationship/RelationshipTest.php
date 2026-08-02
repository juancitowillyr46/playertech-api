<?php

declare(strict_types=1);

namespace AppTests\Unit\Shared\Domain\Relationship;

use App\Shared\Domain\Relationship\Relationship;
use PHPUnit\Framework\TestCase;

final class RelationshipTest extends TestCase
{
    public function testItExposesTheOfficialOptionsInStableOrder(): void
    {
        self::assertSame([
            ['label' => 'Padre', 'value' => 'FATHER'],
            ['label' => 'Madre', 'value' => 'MOTHER'],
            ['label' => 'Abuelo', 'value' => 'GRANDFATHER'],
            ['label' => 'Abuela', 'value' => 'GRANDMA'],
            ['label' => 'Tutor', 'value' => 'TUTOR'],
            ['label' => 'Hermano', 'value' => 'BROTHER'],
            ['label' => 'Hermana', 'value' => 'SISTER'],
            ['label' => 'Otro', 'value' => 'OTHER'],
        ], Relationship::options());
    }

    public function testItNormalizesInputAndRejectsUnsupportedValues(): void
    {
        self::assertSame(Relationship::MOTHER, Relationship::fromInput(' mother '));

        $this->expectException(\InvalidArgumentException::class);
        Relationship::fromInput('cousin');
    }
}
