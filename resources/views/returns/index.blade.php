@extends('layouts.dashboard')

@section('title', 'Gestion des retours')
@section('page-title', 'Gestion des retours')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-arrow-return-left me-2"></i>Gestion des retours</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Retours</li>
      </ol>
    </nav>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('returns.index') }}" class="row g-3 align-items-end">
      <div class="col-md-6">
        <label class="form-label small">Rechercher</label>
        <input type="text" name="search" class="form-control" placeholder="Produit, référence, vente ou client"
               value="{{ $filters['search'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label small">Statut</label>
        <select name="status" class="form-select">
          <option value="">Tous</option>
          <option value="not_returned" @selected(($filters['status'] ?? '') === 'not_returned')>Vendu (non retourné)</option>
          <option value="returned" @selected(($filters['status'] ?? '') === 'returned')>Retourné</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
      </div>
    </form>
  </div>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>Vente</th>
          <th>Date</th>
          <th>Client</th>
          <th>Produit</th>
          <th class="text-center">Qté</th>
          <th class="text-end">Montant</th>
          <th>Statut</th>
          <th class="text-end">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            <td>{{ $item->sale?->sale_number ?? '—' }}</td>
            <td>{{ $item->sale?->sale_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $item->sale?->customer?->full_name ?? 'Client anonyme' }}</td>
            <td>
              {{ $item->product?->name ?? '—' }}
              @if($item->product?->reference)
                <br><small class="text-muted">{{ $item->product->reference }}</small>
              @endif
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-end amount">{{ number_format($item->line_total, 0, ',', ' ') }} FCFA</td>
            <td>
              @if($item->isReturned())
                <span class="badge bg-secondary">Retourné</span>
                <div class="small text-muted mt-1">
                  {{ $item->returned_at->format('d/m/Y H:i') }}
                  @if($item->returnedBy)
                    · {{ $item->returnedBy->name }}
                  @endif
                </div>
              @else
                <span class="badge bg-success">Vendu</span>
              @endif
            </td>
            <td class="text-end">
              @if(!$item->isReturned())
                <form action="{{ route('returns.store', $item) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Confirmer le retour de ce produit ? Il sera remis en stock et son montant déduit du chiffre d\'affaires.')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-return-left me-1"></i>Retourner
                  </button>
                </form>
              @else
                <span class="text-muted small">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">Aucun produit vendu trouvé.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-3 border-top">{{ $items->links() }}</div>
</div>
@endsection
