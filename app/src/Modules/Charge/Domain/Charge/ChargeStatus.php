<?php
declare(strict_types=1);
namespace App\Modules\Charge\Domain\Charge;
final readonly class ChargeStatus
{
    private function __construct(private string $value) {}
    public static function pending(): self { return new self('PENDING'); }
    public static function partial(): self { return new self('PARTIAL'); }
    public static function paid(): self { return new self('PAID'); }
    public function value(): string { return $this->value; }
    public function isPending(): bool { return 'PENDING' === $this->value; }
    public function isPartial(): bool { return 'PARTIAL' === $this->value; }
    public function isPaid(): bool { return 'PAID' === $this->value; }
}
