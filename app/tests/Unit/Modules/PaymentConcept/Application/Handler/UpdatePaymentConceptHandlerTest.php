<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\PaymentConcept\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Command\UpdatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Dto\UpdatePaymentConceptInput;
use App\Modules\PaymentConcept\Application\Handler\UpdatePaymentConceptHandler;
use App\Modules\PaymentConcept\Domain\Exception\PaymentConceptAlreadyExistsException;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class UpdatePaymentConceptHandlerTest extends TestCase
{
    public function testItUpdatesAnExistingPaymentConcept(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new UpdatePaymentConceptHandler(new \App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder($repository), $repository);
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

        $response = $handler(new UpdatePaymentConceptCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $paymentConceptId->value(),
            new UpdatePaymentConceptInput('INSCRIPCION', 'Inscripción', 'Cuota inicial'),
        ));

        self::assertSame('INSCRIPCION', $response->toArray()['code']);
        self::assertSame('Inscripción', $response->toArray()['name']);
    }

    public function testItRejectsDuplicateCodeOnUpdate(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new UpdatePaymentConceptHandler(new \App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder($repository), $repository);
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $firstId = PaymentConceptId::generate();
        $secondId = PaymentConceptId::generate();

        $repository->save(PaymentConcept::create($firstId, $academyId, 'MATRICULA', 'Matrícula', null, AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e')));
        $repository->save(PaymentConcept::create($secondId, $academyId, 'MENSUALIDAD', 'Mensualidad', null, AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e')));

        $this->expectException(PaymentConceptAlreadyExistsException::class);

        $handler(new UpdatePaymentConceptCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $secondId->value(),
            new UpdatePaymentConceptInput('MATRICULA', 'Mensualidad', null),
        ));
    }
}
