<?php
declare(strict_types=1);
namespace App\Modules\Player\Application\Document\Command;
use Symfony\Component\HttpFoundation\File\UploadedFile;
final readonly class CreatePlayerDocumentCommand { public function __construct(public string $actorId, public string $academyId, public string $playerId, public string $documentType, public UploadedFile $file, public ?string $observations = null) {} }
