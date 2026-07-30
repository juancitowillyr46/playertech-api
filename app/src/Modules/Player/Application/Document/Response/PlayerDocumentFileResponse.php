<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Response;

use App\Modules\Player\Domain\Document\PlayerDocument;

readonly class PlayerDocumentFileResponse
{
    public function __construct(public PlayerDocument $document, public string $path) {}
}
