<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Application\Command\CancelPaymentCommand;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class CancelPaymentHandler
{
    public function __construct(private PaymentRepository $paymentRepository) {}
    public function __invoke(CancelPaymentCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);
        $paymentId = new PaymentId($command->paymentId);
        $payment = $this->paymentRepository->findById($academyId, $paymentId) ?? throw new \RuntimeException('Payment not found.');
        $payment->cancel(AuditTrail::create($command->actorId));
        $this->paymentRepository->save($payment);
    }
}
