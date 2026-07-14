<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\PaymentConcept\Application\Handler;
use App\Modules\PaymentConcept\Application\Command\CreatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Dto\CreatePaymentConceptInput;
use App\Modules\PaymentConcept\Application\Handler\CreatePaymentConceptHandler;
use PHPUnit\Framework\TestCase;
final class CreatePaymentConceptHandlerTest extends TestCase
{
    public function testItCreatesPaymentConcept(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new CreatePaymentConceptHandler($repository, new \App\Modules\PaymentConcept\Application\Services\PaymentConceptCodeGenerator($repository));
        $response = $handler(new CreatePaymentConceptCommand('019eec93-9a11-7432-bd04-52306b2b3d8e','019eec93-9a11-7432-bd04-52306b2b3d8f', new CreatePaymentConceptInput('Matrícula','Cobro de ingreso')));
        self::assertSame('MATRICULA', $response->toArray()['code']);
        self::assertCount(1, $repository->items);
    }
    public function testItResolvesCodeCollisionsWithDeterministicSuffix(): void
    {
        $repository = new InMemoryPaymentConceptRepository();
        $handler = new CreatePaymentConceptHandler($repository, new \App\Modules\PaymentConcept\Application\Services\PaymentConceptCodeGenerator($repository));
        $first = $handler(new CreatePaymentConceptCommand('019eec93-9a11-7432-bd04-52306b2b3d8e','019eec93-9a11-7432-bd04-52306b2b3d8f', new CreatePaymentConceptInput('Matrícula','Cobro de ingreso')));
        $second = $handler(new CreatePaymentConceptCommand('019eec93-9a11-7432-bd04-52306b2b3d8e','019eec93-9a11-7432-bd04-52306b2b3d8f', new CreatePaymentConceptInput('Matrícula','Cobro de ingreso')));
        self::assertSame('MATRICULA', $first->toArray()['code']);
        self::assertSame('MATRICULA_2', $second->toArray()['code']);
    }
}
