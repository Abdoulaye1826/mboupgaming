<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use App\Services\ReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function __construct(private readonly ReturnService $returnService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $items = $this->returnService->paginate($filters);

        return view('returns.index', compact('items', 'filters'));
    }

    public function store(SaleItem $saleItem): RedirectResponse
    {
        try {
            $this->returnService->returnItem($saleItem, auth()->id());
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Produit retourné : stock mis à jour et montant déduit du chiffre d\'affaires.');
    }
}
