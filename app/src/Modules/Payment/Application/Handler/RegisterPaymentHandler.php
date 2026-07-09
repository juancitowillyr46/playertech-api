<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Membership\Domain\Membership\MembershipId;
use App\Modules\Membership\Domain\Membership\MembershipRepository;
use App\Modules\Payment\Application\Command\RegisterPaymentCommand;
use App\Modules\Payment\Application\Response\PaymentResponse;
use App\Modules\Payment\Domain\Payment\Payment;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Modules\Payment\Domain\Exception\PaymentNotFoundException;
final readonly class RegisterPaymentHandler
{
    public function __construct(private MembershipRepository $membershipRepository, private PaymentConceptRepository $paymentConceptRepository, private PaymentRepository $paymentRepository) {}
    public function __invoke(RegisterPaymentCommand $command): PaymentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $membershipId = new MembershipId($command->membershipId);
        $playerId = new PlayerId($command->playerId);
        $guardianId = new LegalGuardianId($command->guardianId);
        $paymentConceptId = new PaymentConceptId($command->paymentConceptId);
        $membership = $this->membershipRepository->findActiveByPlayerId($academyId, $playerId);
        if (null === $membership || !$membership->id()->equals($membershipId)) {
            throw new PaymentNotFoundException();
        }
        if (null === $this->paymentConceptRepository->findById($academyId, $paymentConceptId)) {
            throw new PaymentNotFoundException();
        }
        $payment = Payment::create(PaymentId::generate(), $academyId, $membershipId, $playerId, $guardianId, $paymentConceptId, new \DateTimeImmutable($command->paymentDate), (float) $command->amount, $command->notes, AuditTrail::create($command->actorId));
        $this->paymentRepository->save($payment);
        return PaymentResponse::fromPayment($payment);
    }
}
