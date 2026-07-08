<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\PaymentConcept\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Command\DeactivatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Handler\DeactivatePaymentConceptHandler;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class DeactivatePaymentConceptHandlerTest extends TestCase
{
    public function testItDeactivatesAnExistingPaymentConcept(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new DeactivatePaymentConceptHandler(new PaymentConceptFinder($repository), $repository);
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $paymentConceptId = PaymentConceptId::generate();

        $repository->save(PaymentConcept::create(
            $paymentConceptId,
            $academyId,
            'MATRICULA',
            'Matrícula',
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler(new DeactivatePaymentConceptCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $paymentConceptId->value(),
        ));

        self::assertSame('INACTIVE', $repository->findById($academyId, $paymentConceptId)->status()->value());
    }
}
