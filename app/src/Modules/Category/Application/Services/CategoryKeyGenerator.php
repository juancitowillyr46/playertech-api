<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Services;

final readonly class CategoryKeyGenerator
{
    public function generate(string $name): string
    {
        $value = trim($name);
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
        $value = strtoupper($value);
        $value = preg_replace('/[^A-Z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        if ('' === $value) {
            throw new \InvalidArgumentException('Category key cannot be generated from an empty name.');
        }

        return mb_substr($value, 0, 50);
    }
}
