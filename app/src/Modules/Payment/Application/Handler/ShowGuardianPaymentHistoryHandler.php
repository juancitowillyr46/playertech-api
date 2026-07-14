<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Payment\Application\Query\ShowGuardianPaymentHistoryQuery;
use App\Modules\Payment\Application\Response\PaymentHistoryItemResponse;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
final readonly class ShowGuardianPaymentHistoryHandler
{
    public function __construct(private PaymentRepository $paymentRepository) {}

    public function __invoke(ShowGuardianPaymentHistoryQuery $query): array
    {
        return array_values(array_map(
            static fn ($payment) => PaymentHistoryItemResponse::fromPayment($payment),
            array_filter(
                $this->paymentRepository->findAllByAcademy(new AcademyId($query->academyId)),
                static fn ($payment): bool => $payment->guardianId()->equals(new LegalGuardianId($query->guardianId))
            )
        ));
    }
}
