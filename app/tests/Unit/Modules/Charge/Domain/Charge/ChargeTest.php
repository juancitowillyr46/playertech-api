<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Charge\Domain\Charge;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class ChargeTest extends TestCase
{
    public function testItCreatesPendingCharge(): void
    {
        $charge = Charge::create(ChargeId::generate(), AcademyId::generate(), PlayerId::generate(), MembershipId::generate(), PaymentConceptId::generate(), 'Uniforme', 80.00, new \DateTimeImmutable('2026-07-31'), 'MANUAL', AuditTrail::create('actor-id'));
        self::assertTrue($charge->status()->isPending());
    }
}
