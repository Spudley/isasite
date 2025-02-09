<?php
declare(strict_types=1);
namespace App\Tests;

use App\Service\FundNegotiator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FundNegotiatorTest extends TestCase
{
    /**
     * @dataProvider providerNegotiate
     */
    public function testNegotiate(int $amount, string $fund, ?int $expectedUnits, string $testDescription): void
    {
        $negotatior = new FundNegotiator();

        if (is_null($expectedUnits)) {
            $this->expectException(\Exception::class);
        }
        $negotatior->negotiate($amount, $fund, 'test-123');

        $this->assertSame($expectedUnits, $negotatior->units, $testDescription);
    }

    public function providerNegotiate(): array
    {
        return [
            [10000, 'fund-a', 4, 'Should order multiple units.'],
            [10, 'fund-a', null, 'Order amount too low to order any units.'],
            [10000, 'fund-x', null, 'Trying to order from non-existent fund.'],
            [12345, 'cash', 12345, 'Cash order.'],
        ];
    }

    public function testCreateBalancingNegotiator(): void
    {
        $negotiator = new FundNegotiator();
        $negotiator->negotiate(10000, 'fund-b', 'test-123');
        $balancingNegotiator = $negotiator->createBalancingCashNegotiator();

        $this->assertSame('cash', $balancingNegotiator->fund);
        $this->assertSame(1, $balancingNegotiator->unitPrice);
        $this->assertSame(-9936, $balancingNegotiator->units);
    }

    public function testFailCreateBalancingNegotiator(): void
    {
        $negotiator = new FundNegotiator();
        $negotiator->negotiate(10000, 'cash', 'test-123');
        $this->expectExceptionMessage('Cannot balance cash with cash.');
        $balancingNegotiator = $negotiator->createBalancingCashNegotiator();
    }
}
