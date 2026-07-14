<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\PaymentConcept\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptCodeGenerator;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class PaymentConceptCodeGeneratorTest extends TestCase
{
    public function testItNormalizesTheNameIntoAnUppercaseCode(): void
    {
        $repository = new \App\Tests\Unit\Modules\PaymentConcept\Application\Handler\InMemoryPaymentConceptRepository();
        $generator = new PaymentConceptCodeGenerator($repository);

        self::assertSame('SEGURO_DEPORTIVO', $generator->generate(AcademyId::generate(), 'Seguro deportivo'));
    }

    public function testItAddsDeterministicSuffixOnCollision(): void
    {
        $repository = new \App\Tests\Unit\Modules\PaymentConcept\Application\Handler\InMemoryPaymentConceptRepository();
        $generator = new PaymentConceptCodeGenerator($repository);
        $academyId = AcademyId::generate();

        $repository->save(PaymentConcept::create(
            PaymentConceptId::generate(),
            $academyId,
            'MATRICULA',
            'Matrícula',
            null,
            AuditTrail::create('actor-id')
        ));

        self::assertSame('MATRICULA_2', $generator->generate($academyId, 'Matrícula'));
    }
}
