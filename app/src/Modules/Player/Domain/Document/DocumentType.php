<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Document;

enum DocumentType: string
{
    case CE = 'CE';
    case CC = 'CC';
    case TI = 'TI';
    case PPT = 'PPT';
    case PASSPORT = 'PASSPORT';
    case RC = 'RC';

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)) ?? '')
            ?? throw new \InvalidArgumentException('El tipo de documento no es válido.');
    }
}
