<?php

namespace App\Service;

/*
 * We're not actually going to negotiate a unit price with a vendor here, but this class is where that would happen.
 */
class FundNegotiator
{
    //Obviously this in reality, this would be queried with the provider
    //but for now I'm hard coding the prices and spoofing the provider.
    protected const array FUND_PRICE = [
        'fund-a' => 2477,
        'fund-b' => 138,
        'cash' => 1,
    ];

    public protected(set) ?string $fund = null;
    public protected(set) ?int $units = null;
    public protected(set) ?int $unitPrice = null;
    public protected(set) ?string $theirTransactionId = null;
    public protected(set) ?string $ourTransactionId = null;

    public function negotiate(int $amount, string $fund, string $ourTransactionId): void
    {
        $this->ourTransactionId = $ourTransactionId;
        $this->fund = $fund;
        $this->unitPrice = self::FUND_PRICE[$fund] ?? 0;
        if ($this->unitPrice === 0) {
            throw new \Exception("Fund {$fund} has rejected your order request.");
        }
        $this->units = intval($amount / $this->unitPrice);
        $this->theirTransactionId = uniqid($fund.'-', true);

        // successful negotiation! (hopefully)
        if ($this->units === 0) {
            throw new \Exception("Fund {$fund} has rejected your order request.");
        }
    }

    public function createBalancingCashNegotiator(): static
    {
        if ($this->fund === 'cash') {
            throw new \Exception('Cannot balance cash with cash.');
        }
        $cashAmountToBalance = $this->units * $this->unitPrice;
        $balancingNegotiator = new static;
        $balancingNegotiator->ourTransactionId = $this->ourTransactionId;
        $balancingNegotiator->fund = 'cash';
        $balancingNegotiator->unitPrice = 1;
        $balancingNegotiator->units = -$cashAmountToBalance;
        $balancingNegotiator->theirTransactionId = uniqid('cash-', true);
        return $balancingNegotiator;
    }
}
