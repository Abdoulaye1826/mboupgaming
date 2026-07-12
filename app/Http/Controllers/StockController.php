<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'product_id', 'supplier_id']);
        $stockMovements = $this->stockService->paginate($filters);
        $types = $this->stockService->getTypes();

        return view('stock.index', compact('stockMovements', 'types', 'filters'));
    }

    /**
     * Ajustement manuel du stock (boutons +/- sur la fiche produit).
     */
    public function adjust(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->stockService->adjust(
                $product,
                $validated['direction'],
                $validated['quantity'],
                $validated['reason'] ?? null
            );
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }

        $product = $product->fresh();

        if ($request->expectsJson()) {
            return response()->json(['stock_quantity' => $product->stock_quantity]);
        }

        return back()->with('success', 'Stock mis à jour avec succès.');
    }
}
