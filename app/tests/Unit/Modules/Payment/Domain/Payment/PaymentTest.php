<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Domain\Payment;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class PaymentTest extends TestCase
{
    public function testItCreatesRegisteredPayment(): void
    {
        $payment = Payment::create(PaymentId::generate(), AcademyId::generate(), MembershipId::generate(), PlayerId::generate(), LegalGuardianId::generate(), PaymentConceptId::generate(), new \DateTimeImmutable('2026-07-09'), 100.00, null, AuditTrail::create('actor-id'));
        self::assertTrue($payment->status()->isRegistered());
    }
}
