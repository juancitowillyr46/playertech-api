<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Application\Command\CancelPaymentCommand;
use App\Modules\Payment\Application\Handler\CancelPaymentHandler;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class CancelPaymentHandlerTest extends TestCase
{
    public function testItCancelsPayment(): void
    {
        $academyId = AcademyId::generate();
        $payment = Payment::create(PaymentId::generate(), $academyId, MembershipId::generate(), PlayerId::generate(), LegalGuardianId::generate(), PaymentConceptId::generate(), new \DateTimeImmutable('2026-07-09'), 100.00, null, AuditTrail::create('actor-id'));
        $repo = new InMemoryPaymentRepository();
        $repo->save($payment);
        $handler = new CancelPaymentHandler($repo);
        $handler(new CancelPaymentCommand('actor-id', $academyId->value(), $payment->id()->value()));
        self::assertTrue($repo->items[$payment->id()->value()]->status()->isCancelled());
    }
}
