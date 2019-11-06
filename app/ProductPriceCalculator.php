<?php

namespace App;

class ProductPriceCalculator
{
    /**
     * Represents a database of products and prices.
     *
     * @var array
     */
    private $prices = [];

    /**
     * Get the total price for the given quantity of the product.
     *
     * @param string $product_code
     * @param int $quantity
     * @return float
     */
    public function getTotalPrice(string $product_code, int $quantity): float
    {
        $total_price = 0;
        $remaining_items = $quantity;

        while ($remaining_items > 0) {
            $band = $this->getMaxPriceBandForQuantity($product_code, $remaining_items);
            $band_price = $this->prices[$product_code][$band];

            $total_price += $band_price;
            $remaining_items -= $band;
        }

        return $total_price;
    }

    /**
     * Get the highest price/quantity band which fits into a given quantity.
     *
     * @param string $product_code
     * @param int $quantity
     * @return int
     */
    private function getMaxPriceBandForQuantity(string $product_code, int $quantity): int
    {
        $product_prices = $this->prices[$product_code];

        $available_quantities = array_keys($product_prices);

        $max_available_quantity = max($available_quantities);

        while ($max_available_quantity > $quantity) {
            $key = array_search($max_available_quantity, $available_quantities);
            unset($available_quantities[$key]);

            $max_available_quantity = max($available_quantities);
        }

        return $max_available_quantity;
    }

    /**
     * Add a price for a given quantity of a product to the price list.
     *
     * @param string $product_code
     * @param float $price
     * @param int $quantity
     */
    public function addProductPrice(string $product_code, float $price, int $quantity): void
    {
        if (array_key_exists($product_code, $this->prices) === false) {
            $this->prices[$product_code] = [];
        }

        $this->prices[$product_code][$quantity] = $price;
    }
}