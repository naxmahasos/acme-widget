<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CartService
{
    protected array $products = [
        'R01' => ['name' => 'Red Widget', 'price' => 32.95],
        'G01' => ['name' => 'Green Widget', 'price' => 24.95],
        'B01' => ['name' => 'Blue Widget', 'price' => 7.95],
    ];

    protected array $deliveryCharges = [
        50 => 4.95,
        90 => 2.95,
        'free' => 0,
    ];

    protected array $offers = [
        'R01' => ['buy' => 1, 'discount' => 0.5],
    ];

    protected string $cartKey = 'cart';

    public function add($productCode): void
    {
        if (!isset($this->products[$productCode])) {
            throw new \Exception('Invalid product code');
        }

        $cart = Cache::get($this->cartKey, []);

        if (isset($cart[$productCode])) {
            $cart[$productCode]++;
        } else {
            $cart[$productCode] = 1;
        }

        Cache::put($this->cartKey, $cart);
    }

    public function clear(): void
    {
        Cache::forget($this->cartKey);
    }

    public function getCartProducts(): array
    {
        return Cache::get($this->cartKey, []);
    }

    public function total(): float
    {
        $cart = Cache::get($this->cartKey, []);
        $total = 0;

        foreach ($cart as $productCode => $quantity) {
            $product = $this->products[$productCode];
            $total += $product['price'] * $quantity;

            // Apply special offer
            if (isset($this->offers[$productCode])) {
                $offer = $this->offers[$productCode];
                if ($quantity >= $offer['buy'] + 1) {
                    $total -= ($product['price'] * $offer['discount']) * floor($quantity / ($offer['buy'] + 1));
                }
            }
        }

        // Apply delivery charges
        if ($total < 50) {
            $total += $this->deliveryCharges[50];
        } elseif ($total < 90) {
            $total += $this->deliveryCharges[90];
        }

        return round($total, 2);
    }
}
