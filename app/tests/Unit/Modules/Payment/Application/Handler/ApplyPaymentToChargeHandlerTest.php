<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Application\Command\ApplyPaymentToChargeCommand;
use App\Modules\Payment\Application\Handler\ApplyPaymentToChargeHandler;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class ApplyPaymentToChargeHandlerTest extends TestCase
{
    public function testItAppliesPaymentAndMarksChargePaid(): void
    {
        $academyId = AcademyId::generate();
        $payment = Payment::create(PaymentId::generate(), $academyId, MembershipId::generate(), PlayerId::generate(), LegalGuardianId::generate(), PaymentConceptId::generate(), new \DateTimeImmutable('2026-07-09'), 100.00, null, AuditTrail::create('actor-id'));
        $charge = Charge::create(ChargeId::generate(), $academyId, MembershipId::generate(), PaymentConceptId::generate(), 'Uniforme', 100.00, AuditTrail::create('actor-id'));
        $paymentRepo = new InMemoryPaymentRepository();
        $chargeRepo = new InMemoryChargeRepository();
        $allocationRepo = new InMemoryPaymentAllocationRepository();
        $paymentRepo->save($payment);
        $chargeRepo->save($charge);
        $handler = new ApplyPaymentToChargeHandler($paymentRepo, $chargeRepo, $allocationRepo);
        $handler(new ApplyPaymentToChargeCommand('actor-id', $academyId->value(), $payment->id()->value(), $charge->id()->value(), '100.00'));
        self::assertTrue($chargeRepo->items[$charge->id()->value()]->status()->isPaid());
    }
}
