<?php

namespace App\Http\Controllers;

use App\Services\StockService;
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
}
