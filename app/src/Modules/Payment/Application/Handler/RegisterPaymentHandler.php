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
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocation;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationId;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationRepository;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Modules\Payment\Domain\Exception\PaymentNotFoundException;
final readonly class RegisterPaymentHandler
{
    public function __construct(private MembershipRepository $membershipRepository, private PaymentConceptRepository $paymentConceptRepository, private PaymentRepository $paymentRepository, private \App\Modules\Charge\Domain\Charge\ChargeRepository $chargeRepository, private PaymentAllocationRepository $allocationRepository) {}
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
        $payment = Payment::create(PaymentId::generate(), $academyId, $membershipId, $playerId, $guardianId, $paymentConceptId, new \DateTimeImmutable($command->paymentDate), (float) $command->amount, $command->method, $command->notes, AuditTrail::create($command->actorId));
        $this->paymentRepository->save($payment);

        if ([] !== $command->allocations) {
            $totalAllocated = 0.0;

            foreach ($command->allocations as $allocationInput) {
                $chargeId = new \App\Modules\Charge\Domain\Charge\ChargeId((string) ($allocationInput['chargeId'] ?? ''));
                $allocationAmount = (float) ($allocationInput['amount'] ?? 0);
                $charge = $this->chargeRepository->findById($academyId, $chargeId);

                if (null === $charge) {
                    throw new PaymentNotFoundException();
                }

                if (!$charge->playerId()->equals($playerId)) {
                    throw new \InvalidArgumentException('Charge does not belong to the selected player.');
                }

                $charge->applyAllocation($allocationAmount, $command->actorId);
                $this->allocationRepository->save(PaymentAllocation::create(PaymentAllocationId::generate(), $academyId, $payment->id(), $chargeId, $allocationAmount, AuditTrail::create($command->actorId)));
                $this->chargeRepository->save($charge);
                $payment->addAllocation($chargeId->value(), $allocationAmount);
                $totalAllocated += $allocationAmount;
            }

            if (abs($totalAllocated - (float) $command->amount) > 0.00001) {
                throw new \InvalidArgumentException('The total of allocations must match the payment amount.');
            }
        }

        return PaymentResponse::fromPayment($payment);
    }
}
