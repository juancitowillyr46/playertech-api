<?php

declare(strict_types=1);

namespace AppTests\Unit\Shared\Domain\Document;

use App\Shared\Domain\Document\DocumentType;
use PHPUnit\Framework\TestCase;

final class DocumentTypeTest extends TestCase
{
    public function testItExposesTheOfficialOptionsInStableOrder(): void
    {
        self::assertSame([
            ['label' => 'Cédula de extranjería', 'value' => 'CE'],
            ['label' => 'Cédula de ciudadanía', 'value' => 'CC'],
            ['label' => 'Tarjeta de identidad', 'value' => 'TI'],
            ['label' => 'PPT', 'value' => 'PPT'],
            ['label' => 'Pasaporte', 'value' => 'PASSPORT'],
            ['label' => 'Registro civil', 'value' => 'RC'],
        ], DocumentType::options());
    }

    public function testItNormalizesInputAndRejectsUnsupportedValues(): void
    {
        self::assertSame(DocumentType::CC, DocumentType::fromInput(' cc '));
        $this->expectException(\InvalidArgumentException::class);
        DocumentType::fromInput('DNI');
    }
}
