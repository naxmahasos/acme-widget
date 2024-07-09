<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(Request $request): JsonResponse
    {
        $productCode = $request->input('product_code');

        try {
            $this->cartService->add($productCode);
            return response()->json(['message' => 'Product added to cart']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return response()->json(['message' => 'Cart cleared']);
    }

    public function total(): JsonResponse
    {
        $products = $this->cartService->getCartProducts();
        $total = $this->cartService->total();
        return response()->json([
            'products' => $products,
            'total' => $total,
        ]);
    }
}
