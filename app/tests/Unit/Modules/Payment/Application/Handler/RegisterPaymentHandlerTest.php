<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Payment\Application\Command\RegisterPaymentCommand;
use App\Modules\Payment\Application\Handler\RegisterPaymentHandler;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
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
        $membership = Membership::create($membershipId, $academyId, $playerId, $guardianId, AuditTrail::create('actor-id'));
        $membershipRepository->method('findActiveByPlayerId')->willReturn($membership);
        $conceptRepository->method('findById')->willReturn(PaymentConcept::create($conceptId, $academyId, 'MATRICULA', 'Matrícula', null, AuditTrail::create('actor-id')));
        $paymentRepository->expects(self::once())->method('save');
        $handler = new RegisterPaymentHandler($membershipRepository, $conceptRepository, $paymentRepository);
        $response = $handler(new RegisterPaymentCommand('actor-id', $academyId->value(), $membershipId->value(), $playerId->value(), $guardianId->value(), $conceptId->value(), '2026-07-09', '100.00'));
        self::assertSame($membershipId->value(), $response->toArray()['membershipId']);
    }
}
