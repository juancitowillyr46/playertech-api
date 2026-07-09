<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Domain\Charge\ChargeId;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Modules\Payment\Application\Command\ApplyPaymentToChargeCommand;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocation;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationId;
use App\Modules\Payment\Domain\PaymentAllocation\PaymentAllocationRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class ApplyPaymentToChargeHandler
{
    public function __construct(private PaymentRepository $paymentRepository, private ChargeRepository $chargeRepository, private PaymentAllocationRepository $allocationRepository) {}
    public function __invoke(ApplyPaymentToChargeCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);
        $paymentId = new PaymentId($command->paymentId);
        $chargeId = new ChargeId($command->chargeId);
        $payment = $this->paymentRepository->findById($academyId, $paymentId) ?? throw new \RuntimeException('Payment not found.');
        $charge = $this->chargeRepository->findById($academyId, $chargeId) ?? throw new \RuntimeException('Charge not found.');
        if (null !== $this->allocationRepository->findByPaymentAndCharge($academyId, $paymentId->value(), $chargeId->value())) { return; }
        $amount = (float) $command->amount;
        $allocation = PaymentAllocation::create(PaymentAllocationId::generate(), $academyId, $paymentId, $chargeId, $amount, AuditTrail::create($command->actorId));
        $charge->markPaid($command->actorId);
        $this->allocationRepository->save($allocation);
        $this->chargeRepository->save($charge);
    }
}
