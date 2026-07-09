<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\PaymentConcept\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Handler\ListPaymentConceptsHandler;
use App\Modules\PaymentConcept\Application\Query\ListPaymentConceptsQuery;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Application\Pagination\PaginationQuery;
use PHPUnit\Framework\TestCase;

final class ListPaymentConceptsHandlerTest extends TestCase
{
    public function testItListsPaymentConceptsForTheCurrentAcademy(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new ListPaymentConceptsHandler($repository);
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');

        $repository->save(PaymentConcept::create(
            PaymentConceptId::generate(),
            $academyId,
            'MATRICULA',
            'Matrícula',
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $items = $handler(new ListPaymentConceptsQuery($academyId, new PaginationQuery()));

        self::assertCount(1, $items->items);
        self::assertSame('MATRICULA', $items->items[0]->toArray()['code']);
    }
}
