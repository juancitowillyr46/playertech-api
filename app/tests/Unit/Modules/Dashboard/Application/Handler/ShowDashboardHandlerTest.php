<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Dashboard\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Modules\Dashboard\Application\Handler\ShowDashboardHandler;
use App\Modules\Dashboard\Application\Query\ShowDashboardQuery;
use PHPUnit\Framework\TestCase;
final class ShowDashboardHandlerTest extends TestCase
{
    public function testItShowsDashboardMetrics(): void
    {
        $academyId = AcademyId::generate();
        $playerRepo = new InMemoryPlayerRepository();
        $membershipRepo = new InMemoryMembershipRepository();
        $chargeRepo = new InMemoryChargeRepository();
        $paymentRepo = new InMemoryPaymentRepository();
        $player = Player::create(PlayerId::generate(), $academyId, 'Juan', 'Pérez', new \DateTimeImmutable('2014-05-18'), '12345678', null, null, AuditTrail::create('actor-id'));
        $playerRepo->save($player);
        $membership = Membership::create(MembershipId::generate(), $academyId, $player->id(), \App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId::generate(), AuditTrail::create('actor-id'));
        $membershipRepo->save($membership);
        $chargeRepo->save(Charge::create(ChargeId::generate(), $academyId, $membership->id(), PaymentConceptId::generate(), 'Uniforme', 80.00, AuditTrail::create('actor-id')));
        $paymentRepo->save(Payment::create(PaymentId::generate(), $academyId, $membership->id(), $player->id(), \App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId::generate(), PaymentConceptId::generate(), new \DateTimeImmutable('2026-07-09'), 80.00, null, AuditTrail::create('actor-id')));
        $handler = new ShowDashboardHandler($playerRepo, $membershipRepo, $chargeRepo, $paymentRepo);
        $response = $handler(new ShowDashboardQuery($academyId->value()));
        self::assertSame(1, $response->toArray()['active_players']);
        self::assertSame(1, $response->toArray()['pending_charges']);
    }
}
