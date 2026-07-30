<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Command;
final readonly class DeletePlayerDocumentCommand { public function __construct(public string $actorId, public string $academyId, public string $playerId, public string $documentId) {} }
