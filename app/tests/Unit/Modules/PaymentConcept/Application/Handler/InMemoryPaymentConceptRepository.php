<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\PaymentConcept\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConcept;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptId;
use App\Modules\PaymentConcept\Domain\PaymentConcept\PaymentConceptRepository;
use App\Shared\Application\Pagination\PaginationQuery;
final class InMemoryPaymentConceptRepository implements PaymentConceptRepository
{
    public array $items = [];
    public function save(PaymentConcept $paymentConcept): void { $this->items[$paymentConcept->id()->value()] = $paymentConcept; }
    public function findById(AcademyId $academyId, PaymentConceptId $paymentConceptId): ?PaymentConcept { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->id()->value()===$paymentConceptId->value()) return $item; } return null; }
    public function findByCode(AcademyId $academyId, string $code): ?PaymentConcept { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->code()===strtoupper(trim($code))) return $item; } return null; }
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array {
        $items = array_values(array_filter($this->items, fn($item) => $item->academyId()->value()===$academyId->value()));
        return ['items' => array_slice($items, ($pagination->page - 1) * $pagination->perPage, $pagination->perPage), 'total' => count($items)];
    }
}
