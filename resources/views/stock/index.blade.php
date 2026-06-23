@extends('layouts.dashboard')

@section('title', 'Stock')
@section('page-title', 'Gestion des mouvements de stock')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-box-seam me-2"></i>Stock</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Stock</li>
      </ol>
    </nav>
  </div>
</div>

<div class="mb-3">
  <span class="badge bg-primary fs-6">{{ $stockMovements->total() }} mouvement(s) de stock</span>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('stock.index') }}" id="stockFilterForm" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label small">Rechercher</label>
        <input type="text" name="search" class="form-control" placeholder="Produit, fournisseur, référence, motif..."
               value="{{ $filters['search'] ?? '' }}">
      </div>
      <div class="col-md-2">
        <label class="form-label small">Type</label>
        <select name="type" class="form-select">
          <option value="">Tous</option>
          @foreach($types as $value => $label)
            <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small">Produit</label>
        <input type="number" name="product_id" class="form-control" value="{{ $filters['product_id'] ?? '' }}" placeholder="ID produit">
      </div>
      <div class="col-md-2">
        <label class="form-label small">Fournisseur</label>
        <input type="number" name="supplier_id" class="form-control" value="{{ $filters['supplier_id'] ?? '' }}" placeholder="ID fournisseur">
      </div>
      <div class="col-md-3 text-end">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Type</th>
          <th>Produit</th>
          <th>Fournisseur</th>
          <th>Utilisateur</th>
          <th class="text-end">Quantité</th>
          <th class="text-end">Avant</th>
          <th class="text-end">Après</th>
          <th>Référence</th>
          <th>Motif</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($stockMovements as $movement)
          <tr>
            <td>{{ $movement->id }}</td>
            <td>{{ $movement->type?->label() ?? ucfirst($movement->type) }}</td>
            <td>
              @if($movement->product)
                <strong>{{ $movement->product->name }}</strong><br>
                <small class="text-muted">{{ $movement->product->reference }}</small>
              @else
                <span class="text-muted">Produit supprimé</span>
              @endif
            </td>
            <td>
              @if($movement->supplier)
                {{ $movement->supplier->name }}
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>{{ $movement->user?->name ?? 'Système' }}</td>
            <td class="text-end">{{ $movement->quantity }}</td>
            <td class="text-end">{{ $movement->quantity_before }}</td>
            <td class="text-end">{{ $movement->quantity_after }}</td>
            <td>{{ $movement->reference ?? '—' }}</td>
            <td>{{ $movement->reason ?? '—' }}</td>
            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="11" class="text-center text-muted py-4">Aucun mouvement de stock trouvé.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-3 border-top">{{ $stockMovements->links() }}</div>
</div>
@endsection
