<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Document;

use App\Modules\Player\Infrastructure\Persistence\PlayerDocumentUploadValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PlayerDocumentUploadValidatorTest extends TestCase
{
    public function testItAcceptsSupportedPdfAndSanitizesOriginalName(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'document'); file_put_contents($path, '%PDF-test');
        $result = (new PlayerDocumentUploadValidator())->validate(new UploadedFile($path, "cedula <script>.pdf", 'application/pdf', null, true));
        self::assertSame('pdf', $result['extension']); self::assertSame('cedula script.pdf', $result['originalFileName']);
    }

    public function testItRejectsUnsupportedMimeType(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'document'); file_put_contents($path, 'plain');
        $this->expectException(\InvalidArgumentException::class);
        (new PlayerDocumentUploadValidator())->validate(new UploadedFile($path, 'file.txt', 'text/plain', null, true));
    }

    public function testItRejectsFilesLargerThanThreeMegabytes(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'document'); $handle = fopen($path, 'wb'); fseek($handle, 3145729 - 1); fwrite($handle, 'x'); fclose($handle);
        $this->expectException(\InvalidArgumentException::class);
        (new PlayerDocumentUploadValidator())->validate(new UploadedFile($path, 'large.pdf', 'application/pdf', null, true));
    }
}
