<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Document;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Document\Command\CreatePlayerDocumentCommand;
use App\Modules\Player\Application\Document\Handler\CreatePlayerDocumentHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Document\{PlayerDocumentRepository,PlayerDocumentStorage,PlayerDocumentUploadValidator};
use App\Modules\Player\Domain\Player\{Player,PlayerId,PlayerRepository};
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CreatePlayerDocumentHandlerTest extends TestCase
{
    public function testInvalidDocumentTypeDoesNotStoreAFile(): void
    {
        $academyId = AcademyId::generate();
        $player = Player::create(PlayerId::generate(), $academyId, 'CC', 'Ana', 'Rojas', new \DateTimeImmutable('2010-01-01'), '100', null, null, null, null, null, null, null, null, AuditTrail::create('actor'));
        $players = $this->createMock(PlayerRepository::class);
        $players->method('findById')->willReturn($player);
        $storage = $this->createMock(PlayerDocumentStorage::class);
        $storage->expects(self::never())->method('store');
        $validator = $this->createMock(PlayerDocumentUploadValidator::class);
        $validator->expects(self::never())->method('validate');
        $repository = $this->createMock(PlayerDocumentRepository::class);
        $path = tempnam(sys_get_temp_dir(), 'document');
        file_put_contents($path, '%PDF-test');
        $this->expectException(\InvalidArgumentException::class);
        (new CreatePlayerDocumentHandler(new PlayerFinder($players), $repository, $storage, $validator))(new CreatePlayerDocumentCommand('actor', $academyId->value(), $player->id()->value(), 'INVALID', new UploadedFile($path, 'document.pdf', 'application/pdf', null, true)));
    }
}
