<?php
declare(strict_types=1);
namespace App\Modules\PaymentConcept\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Application\Command\DeactivatePaymentConceptCommand;
use App\Modules\PaymentConcept\Application\Services\PaymentConceptFinder;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
final readonly class DeactivatePaymentConceptHandler
{
    public function __construct(private PaymentConceptFinder $finder, private PaymentConceptRepository $repository) {}
    public function __invoke(DeactivatePaymentConceptCommand $command): void
    {
        $paymentConcept = $this->finder->findOrFail(new AcademyId($command->academyId), new \App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId($command->paymentConceptId));
        $paymentConcept->deactivate($command->actorId);
        $this->repository->save($paymentConcept);
    }
}
