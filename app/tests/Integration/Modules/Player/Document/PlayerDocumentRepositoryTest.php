<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Player\Document;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Document\{PlayerDocument,PlayerDocumentId};
use App\Modules\Player\Domain\Player\{Player,PlayerId};
use App\Modules\Player\Infrastructure\Persistence\PlayerDocumentRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Tests\Support\Database\SchemaResetter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PlayerDocumentRepositoryTest extends KernelTestCase
{
    public function testItListsOnlyActiveDocumentsForThePlayerAndAcademy(): void
    {
        self::bootKernel();
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        SchemaResetter::reset($em, [$em->getClassMetadata(Player::class), $em->getClassMetadata(PlayerDocument::class)]);

        $academy = AcademyId::generate();
        $otherAcademy = AcademyId::generate();
        $player = Player::create(PlayerId::generate(), $academy, 'CC', 'Ana', 'Rojas', new \DateTimeImmutable('2010-01-01'), '100', null, null, null, null, null, null, null, null, AuditTrail::create('actor'));
        $otherPlayer = Player::create(PlayerId::generate(), $otherAcademy, 'CC', 'Luis', 'Diaz', new \DateTimeImmutable('2010-01-01'), '200', null, null, null, null, null, null, null, null, AuditTrail::create('actor'));
        $em->persist($player);
        $em->persist($otherPlayer);
        $em->flush();

        $file = static fn(string $name): array => ['originalFileName' => $name, 'storageName' => uniqid('doc-', true) . '.pdf', 'mimeType' => 'application/pdf', 'size' => 10, 'extension' => 'pdf'];
        $active = PlayerDocument::create(PlayerDocumentId::generate(), $academy, $player->id(), DocumentType::CC, $file('cc.pdf'), null, 'actor');
        $deleted = PlayerDocument::create(PlayerDocumentId::generate(), $academy, $player->id(), DocumentType::CE, $file('ce.pdf'), null, 'actor');
        $deleted->delete('actor');
        $crossTenant = PlayerDocument::create(PlayerDocumentId::generate(), $otherAcademy, $otherPlayer->id(), DocumentType::TI, $file('ti.pdf'), null, 'actor');
        $em->persist($active);
        $em->persist($deleted);
        $em->persist($crossTenant);
        $em->flush();

        $repository = new PlayerDocumentRepository($doctrine);
        $result = $repository->findActiveByPlayer($academy, $player->id(), new PaginationQuery());
        self::assertSame(1, $result['total']);
        self::assertSame($active->id()->value(), $result['items'][0]->id()->value());
    }
}
