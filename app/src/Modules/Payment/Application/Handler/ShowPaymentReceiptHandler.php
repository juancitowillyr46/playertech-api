<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Payment\Application\Query\ShowPaymentReceiptQuery;
use App\Modules\Payment\Application\Response\PaymentReceiptResponse;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ShowPaymentReceiptHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private PaymentConceptRepository $paymentConceptRepository,
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(ShowPaymentReceiptQuery $query): PaymentReceiptResponse
    {
        $academyId = new AcademyId($query->academyId);
        $payment = $this->paymentRepository->findById($academyId, new PaymentId($query->paymentId));

        if (null === $payment) {
            throw new NotFoundHttpException('Pago no encontrado.');
        }

        $concept = $this->paymentConceptRepository->findById($academyId, $payment->paymentConceptId());
        if (null === $concept) {
            throw new NotFoundHttpException('Concepto de pago no encontrado.');
        }

        $academy = $this->academyRepository->findById($academyId);
        if (null === $academy) {
            throw new NotFoundHttpException('Academia no encontrada.');
        }

        return PaymentReceiptResponse::fromPayment(
            $payment,
            $concept->code()->value(),
            $concept->name()->value(),
            $academy->taxIdType(),
            $academy->taxIdNumber(),
            $academy->taxRegime(),
            $academy->billingEmail(),
            $academy->address()?->value(),
            $academy->city()?->value(),
        );
    }
}
