<?php
declare(strict_types=1);
namespace App\Modules\Payment\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Application\Query\ShowPaymentHistoryQuery;
use App\Modules\Payment\Application\Response\PaymentHistoryItemResponse;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
final readonly class ShowPaymentHistoryHandler
{
    public function __construct(private PaymentRepository $paymentRepository) {}
    public function __invoke(ShowPaymentHistoryQuery $query): array
    {
        return array_map(static fn ($payment) => PaymentHistoryItemResponse::fromPayment($payment), $this->paymentRepository->findAllByAcademy(new AcademyId($query->academyId)));
    }
}
