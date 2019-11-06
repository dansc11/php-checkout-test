<?php


namespace Tests;

use App\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    /**
     * Strings representing each product code.
     */
    const PRODUCT_ZA = "ZA";
    const PRODUCT_YB = "YB";
    const PRODUCT_FC = "FC";
    const PRODUCT_GD = "GD";

    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        $this->terminal = new Terminal;

        $this->terminal->setPricing(self::PRODUCT_ZA, 2);
        $this->terminal->setPricing(self::PRODUCT_ZA, 7, 4);
        $this->terminal->setPricing(self::PRODUCT_YB, 12);
        $this->terminal->setPricing(self::PRODUCT_FC, 1.25);
        $this->terminal->setPricing(self::PRODUCT_FC, 6, 6);
        $this->terminal->setPricing(self::PRODUCT_GD, 0.15);
    }

    /**
     * Test that the correct total price is calculated for each combination of products.
     *
     * @dataProvider itemProvider
     * @param array $items
     * @param float $expected_total
     */
    public function testTotalPrices(array $items, float $expected_total)
    {
        foreach ($items as $item) {
            $this->terminal->scanItem($item);
        }

        $this->assertEquals($expected_total, $this->terminal->getTotal());
    }

    /**
     * Generates combinations of items and their expected total value for use in testTotalPrices.
     *
     * @return array
     */
    public function itemProvider(): array
    {
        return [
            [[
                self::PRODUCT_ZA,
                self::PRODUCT_YB,
                self::PRODUCT_FC,
                self::PRODUCT_GD,
                self::PRODUCT_ZA,
                self::PRODUCT_YB,
                self::PRODUCT_ZA,
                self::PRODUCT_ZA
            ], 32.40],
            [[
                self::PRODUCT_FC,
                self::PRODUCT_FC,
                self::PRODUCT_FC,
                self::PRODUCT_FC,
                self::PRODUCT_FC,
                self::PRODUCT_FC,
                self::PRODUCT_FC
            ], 7.25],
            [[
                self::PRODUCT_ZA,
                self::PRODUCT_YB,
                self::PRODUCT_FC,
                self::PRODUCT_GD
            ], 15.40]
        ];
    }
}