@extends('layouts.dashboard')

@section('title', 'Modifier une facture')
@section('page-title', 'Modifier la facture')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-receipt me-2"></i>Modifier la facture {{ $invoice->invoice_number }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Factures</a></li>
        <li class="breadcrumb-item active">Modifier</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-chevron-left me-1"></i>Retour
  </a>
</div>

<div class="form-shell u-animate">
  <form action="{{ route('invoices.update', $invoice) }}" method="POST" data-ui-form novalidate>
    @csrf
    @method('PUT')
    <div class="form-card">
      <div class="form-card__header">
        <h2><i class="bi bi-receipt"></i>Fiche facture</h2>
        <p class="form-card__subtitle">Mettez à jour la vente associée, les montants ou le statut de cette facture.</p>
      </div>
      <div class="form-card__body">
        @include('invoices._form', ['invoice' => $invoice])
      </div>
      <div class="form-card__footer">
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
      </div>
    </div>
  </form>
</div>
@endsection
