<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Command\UpdatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Response\PaymentConceptResponse;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
final readonly class UpdatePaymentConceptHandler
{
    public function __construct(private PaymentConceptFinder $finder, private PaymentConceptRepository $repository) {}
    public function __invoke(UpdatePaymentConceptCommand $command): PaymentConceptResponse
    {
        $academyId = new AcademyId($command->academyId);
        $paymentConcept = $this->finder->findOrFail($academyId, new \App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId($command->paymentConceptId));
        $paymentConcept->update($command->input->name ?? '', $command->input->description, $command->actorId);
        $this->repository->save($paymentConcept);
        return PaymentConceptResponse::fromPaymentConcept($paymentConcept);
    }
}
