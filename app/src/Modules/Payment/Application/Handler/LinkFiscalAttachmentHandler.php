<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Payment\Application\Command\LinkFiscalAttachmentCommand;
use App\Modules\Payment\Application\Response\FiscalAttachmentResponse;
use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachment;
use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachmentId;
use App\Modules\Payment\Domain\FiscalAttachment\FiscalAttachmentRepository;
use App\Modules\Payment\Domain\Payment\PaymentId;
use App\Modules\Payment\Domain\Payment\PaymentRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class LinkFiscalAttachmentHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private FiscalAttachmentRepository $fiscalAttachmentRepository,
    ) {
    }

    public function __invoke(LinkFiscalAttachmentCommand $command): FiscalAttachmentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $paymentId = new PaymentId($command->paymentId);

        if (null === $this->paymentRepository->findById($academyId, $paymentId)) {
            throw new NotFoundHttpException('Pago no encontrado.');
        }

        $attachment = FiscalAttachment::create(
            FiscalAttachmentId::generate(),
            $academyId,
            $paymentId,
            $command->providerName,
            $command->documentNumber,
            $command->documentUrl,
            $command->status,
            AuditTrail::create($command->actorId)
        );

        $this->fiscalAttachmentRepository->save($attachment);

        return FiscalAttachmentResponse::fromAttachment($attachment);
    }
}
