<?php

declare(strict_types=1);

namespace App\Modules\PaymentConcept\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;

final readonly class PaymentConceptCodeGenerator
{
    public function __construct(
        private PaymentConceptRepository $repository,
    ) {
    }

    public function generate(AcademyId $academyId, string $name): string
    {
        $baseCode = $this->normalize($name);
        $candidate = $baseCode;
        $suffix = 2;

        while (null !== $this->repository->findByCode($academyId, $candidate)) {
            $candidate = $baseCode.'_'.$suffix;
            ++$suffix;
        }

        return $candidate;
    }

    private function normalize(string $name): string
    {
        $value = trim($name);
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
        $value = preg_replace('/[^A-Za-z0-9]+/', '_', $value) ?? '';
        $value = trim($value, '_');
        $value = strtoupper($value);

        if ('' === $value) {
            throw new \InvalidArgumentException('Payment concept name cannot be converted into a code.');
        }

        return $value;
    }
}
