<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Document\Response;

final readonly class PlayerDocumentResponse extends PlayerDocumentItemResponse
{
    public static function fromDocument(\App\Modules\Player\Domain\Document\PlayerDocument $document): self
    {
        return new self($document);
    }
}
