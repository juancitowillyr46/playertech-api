<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Application\Command\RegisterPaymentCommand;
use App\Modules\Payment\Application\Handler\RegisterPaymentHandler;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationRepository;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\Charge\Domain\Charge\Charge;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class RegisterPaymentHandlerTest extends TestCase
{
    public function testItRegistersPayment(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $membershipId = MembershipId::generate();
        $guardianId = LegalGuardianId::generate();
        $conceptId = PaymentConceptId::generate();
        $membershipRepository = $this->createMock(\App\Modules\Membership\Domain\Membership\MembershipRepository::class);
        $conceptRepository = $this->createMock(\App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository::class);
        $paymentRepository = $this->createMock(PaymentRepository::class);
        $chargeRepository = $this->createMock(ChargeRepository::class);
        $allocationRepository = $this->createMock(PaymentAllocationRepository::class);
        $membership = Membership::create($membershipId, $academyId, $playerId, $guardianId, AuditTrail::create('actor-id'));
        $membershipRepository->method('findActiveByPlayerId')->willReturn($membership);
        $conceptRepository->method('findById')->willReturn(PaymentConcept::create($conceptId, $academyId, 'MATRICULA', 'Matrícula', null, AuditTrail::create('actor-id')));
        $paymentRepository->expects(self::once())->method('save');
        $chargeRepository->expects(self::never())->method('save');
        $allocationRepository->expects(self::never())->method('save');
        $handler = new RegisterPaymentHandler($membershipRepository, $conceptRepository, $paymentRepository, $chargeRepository, $allocationRepository);
        $response = $handler(new RegisterPaymentCommand('actor-id', $academyId->value(), $membershipId->value(), $playerId->value(), $guardianId->value(), $conceptId->value(), '2026-07-09', '100.00', 'CASH'));
        self::assertSame($membershipId->value(), $response->toArray()['membershipId']);
    }

    public function testItRegistersPaymentWithAllocations(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $membershipId = MembershipId::generate();
        $guardianId = LegalGuardianId::generate();
        $conceptId = PaymentConceptId::generate();
        $chargeId = ChargeId::generate();
        $membershipRepository = $this->createMock(\App\Modules\Membership\Domain\Membership\MembershipRepository::class);
        $conceptRepository = $this->createMock(\App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository::class);
        $paymentRepository = $this->createMock(PaymentRepository::class);
        $chargeRepository = $this->createMock(ChargeRepository::class);
        $allocationRepository = $this->createMock(PaymentAllocationRepository::class);
        $membership = Membership::create($membershipId, $academyId, $playerId, $guardianId, AuditTrail::create('actor-id'));
        $charge = Charge::create($chargeId, $academyId, $playerId, $membershipId, $conceptId, 'Uniforme', 100.00, new \DateTimeImmutable('2026-07-31'), 'MANUAL', AuditTrail::create('actor-id'));
        $membershipRepository->method('findActiveByPlayerId')->willReturn($membership);
        $conceptRepository->method('findById')->willReturn(PaymentConcept::create($conceptId, $academyId, 'MATRICULA', 'Matrícula', null, AuditTrail::create('actor-id')));
        $chargeRepository->method('findById')->willReturn($charge);
        $paymentRepository->expects(self::once())->method('save');
        $chargeRepository->expects(self::once())->method('save');
        $allocationRepository->expects(self::once())->method('save');
        $handler = new RegisterPaymentHandler($membershipRepository, $conceptRepository, $paymentRepository, $chargeRepository, $allocationRepository);
        $response = $handler(new RegisterPaymentCommand('actor-id', $academyId->value(), $membershipId->value(), $playerId->value(), $guardianId->value(), $conceptId->value(), '2026-07-09', '100.00', 'CASH', [['chargeId' => $chargeId->value(), 'amount' => '100.00']]));
        self::assertSame(1, count($response->toArray()['allocations']));
    }
}
