<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Player\Document;

use App\Modules\Academy\Domain\Academy\{Academy,AcademyId};
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Player\Domain\Player\{Player,PlayerId};
use App\Shared\Domain\ValueObject\{Address,AuditTrail,City,Email,PhoneNumber,Name};
use App\Tests\Support\Database\{SchemaResetter,TestDatabaseKernel};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\{File\UploadedFile,Request};

final class PlayerDocumentControllerTest extends TestDatabaseKernel
{
    private EntityManagerInterface $entityManager;
    private string $token;
    private string $playerId;

    protected function setUp(): void
    {
        $container = $this->bootTestKernel(); $this->entityManager = $this->entityManager($container);
        SchemaResetter::reset($this->entityManager, array_map(fn (string $class) => $this->entityManager->getClassMetadata($class), [Academy::class, AccountUser::class, Player::class, \App\Modules\Player\Domain\Document\PlayerDocument::class]));
        $academy = Academy::create(AcademyId::generate(), new Name('Academia Documentos'), new Email('documents@test.local'), new PhoneNumber('+57 300 111 2233'), 'Colombia', 'Cundinamarca', null, null, null, null, 'signup', new Address('Calle 1'), new City('Bogota'), null, AuditTrail::create('system'));
        $player = Player::create(PlayerId::generate(), $academy->id(), 'CC', 'Ana', 'Rojas', new \DateTimeImmutable('2010-01-01'), 'DOC-100', null, null, null, null, null, null, null, null, AuditTrail::create('system'));
        $user = new AccountUser(); $user->setEmail('documents-admin@test.local'); $user->setPasswordHash('hash'); $user->setAcademyId($academy->id()->value()); $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN); $user->setStatus(AccountUser::STATUS_ACTIVE); $user->setFullName('Document Admin');
        $this->entityManager->persist($academy); $this->entityManager->persist($player); $this->entityManager->persist($user); $this->entityManager->flush();
        $this->playerId = $player->id()->value(); $this->token = $this->jwtManager($container)->create($user);
    }

    public function testItUploadsListsViewsDownloadsReplacesAndDeletesDocument(): void
    {
        $first = $this->upload('first.pdf', '%PDF-1.4 first');
        self::assertSame(201, $first->getStatusCode()); $documentId = json_decode($first->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['id'];
        $list = $this->request('/api/v1/academy/players/'.$this->playerId.'/documents', 'GET'); self::assertSame(200, $list->getStatusCode(), $list->getContent()); self::assertCount(1, json_decode($list->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']);
        $view = $this->request('/api/v1/academy/players/'.$this->playerId.'/documents/'.$documentId, 'GET'); self::assertSame(200, $view->getStatusCode()); self::assertStringContainsString('inline', (string) $view->headers->get('Content-Disposition'));
        $download = $this->request('/api/v1/academy/players/'.$this->playerId.'/documents/'.$documentId.'/download', 'GET'); self::assertSame(200, $download->getStatusCode()); self::assertStringContainsString('attachment', (string) $download->headers->get('Content-Disposition'));
        $replacement = $this->upload('second.pdf', '%PDF-1.4 second', 'PUT', $documentId); self::assertSame(200, $replacement->getStatusCode()); self::assertSame($documentId, json_decode($replacement->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']['id']);
        $delete = $this->request('/api/v1/academy/players/'.$this->playerId.'/documents/'.$documentId, 'DELETE'); self::assertSame(204, $delete->getStatusCode());
        $empty = $this->request('/api/v1/academy/players/'.$this->playerId.'/documents', 'GET'); self::assertCount(0, json_decode($empty->getContent(), true, 512, JSON_THROW_ON_ERROR)['data']);
    }

    private function upload(string $name, string $contents, string $method = 'POST', ?string $documentId = null): \Symfony\Component\HttpFoundation\Response
    {
        $path = tempnam(sys_get_temp_dir(), 'player-document'); file_put_contents($path, $contents); $file = new UploadedFile($path, $name, 'application/pdf', null, true);
        $suffix = null === $documentId ? '' : '/'.$documentId; $request = Request::create('/api/v1/academy/players/'.$this->playerId.'/documents'.$suffix, $method, parameters: ['documentType' => 'CC', 'observations' => 'Identidad'], files: ['file' => $file], server: ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token]);
        return self::$kernel->handle($request);
    }

    private function request(string $uri, string $method): \Symfony\Component\HttpFoundation\Response
    {
        return self::$kernel->handle(Request::create($uri, $method, server: ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token]));
    }
}
