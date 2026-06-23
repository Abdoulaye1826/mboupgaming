@extends('layouts.dashboard')

@section('title', $product->name)
@section('page-title', 'Détail produit')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1>{{ $product->name }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produits</a></li>
        <li class="breadcrumb-item active">{{ $product->reference }}</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
      <i class="bi bi-pencil me-1"></i>Modifier
    </a>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Retour</a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center">
        @if($product->image)
          <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
               class="img-fluid rounded mb-3" style="max-height:220px;object-fit:contain">
        @else
          <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height:180px">
            <i class="bi bi-controller" style="font-size:4rem;color:#cbd5e1"></i>
          </div>
        @endif
        <h5 class="fw-bold">{{ $product->name }}</h5>
        <p class="text-muted mb-2">{{ $product->brand }}</p>
        <span class="badge bg-light text-dark">{{ $product->category?->name }}</span>
        @if($product->is_active)
          <span class="badge bg-success ms-1">Actif</span>
        @else
          <span class="badge bg-secondary ms-1">Inactif</span>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">Informations</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <small class="text-muted d-block">Référence</small>
            <strong><code>{{ $product->reference }}</code></strong>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Code-barres</small>
            <strong>{{ $product->barcode ?? '—' }}</strong>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Stock</small>
            <strong class="{{ $product->isOutOfStock() ? 'text-danger' : '' }}">
              {{ $product->stock_quantity }} / min {{ $product->minimum_stock }}
            </strong>
            @if($product->isOutOfStock())
              <span class="badge bg-danger ms-1">Rupture</span>
            @elseif($product->isLowStock())
              <span class="badge bg-warning text-dark ms-1">Stock faible</span>
            @endif
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Prix achat</small>
            <strong>{{ number_format($product->purchase_price, 0, ',', ' ') }} FCFA</strong>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Prix vente</small>
            <strong class="text-primary">{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</strong>
          </div>
          <div class="col-md-4">
            <small class="text-muted d-block">Marge</small>
            <strong>{{ number_format($product->margin, 0, ',', ' ') }} FCFA ({{ $product->margin_rate }}%)</strong>
          </div>
          @if($product->description)
            <div class="col-12">
              <small class="text-muted d-block">Description</small>
              <p class="mb-0">{{ $product->description }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    @if($product->stockMovements->isNotEmpty())
      @include('products.partials.stock-movements')
    @endif
  </div>
</div>
@endsection
