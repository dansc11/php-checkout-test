<?php

namespace App;

class Terminal
{
    /**
     * @var array
     */
    private $basket = [];

    /**
     * @var ProductPriceCalculator
     */
    private $product_price_calculator;

    /**
     * Terminal constructor.
     */
    public function __construct()
    {
        $this->product_price_calculator = new ProductPriceCalculator;
    }

    /**
     * Add a new pricing rule for a product to the product price calculator.
     *
     * @param string $product_code
     * @param float $price
     * @param int $quantity
     */
    public function setPricing(string $product_code, float $price, int $quantity = 1): void
    {
        $this->product_price_calculator->addProductPrice($product_code, $price, $quantity);
    }

    /**
     * Add an item to the basket.
     *
     * @param string $product_code
     */
    public function scanItem(string $product_code): void
    {
        $this->addBasketRow($product_code);
        $this->basket[$product_code] += 1;
    }

    /**
     * Add a new item to the basket if it doesn't already exist.
     *
     * @param string $product_code
     */
    private function addBasketRow(string $product_code): void
    {
        if (array_key_exists($product_code, $this->basket)) {
            return;
        }

        $this->basket[$product_code] = 0;
    }

    /**
     * Get the total value of the basket.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->basket as $product => $quantity) {
            $total += $this->product_price_calculator->getTotalPrice($product, $quantity);
        }

        return $total;
    }
}