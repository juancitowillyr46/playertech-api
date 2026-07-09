<?php
declare(strict_types=1);
namespace App\Modules\Payment\Domain\Payment;
final readonly class PaymentStatus
{
    private function __construct(private string $value) {}
    public static function registered(): self { return new self('REGISTERED'); }
    public static function cancelled(): self { return new self('CANCELLED'); }
    public function value(): string { return $this->value; }
    public function isRegistered(): bool { return 'REGISTERED' === $this->value; }
    public function isCancelled(): bool { return 'CANCELLED' === $this->value; }
}
