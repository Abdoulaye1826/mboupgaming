@extends('layouts.dashboard')

@section('title', 'Créer une facture')
@section('page-title', 'Nouvelle facture')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-receipt me-2"></i>Nouvelle facture</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Factures</a></li>
        <li class="breadcrumb-item active">Créer</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-chevron-left me-1"></i>Retour
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="{{ route('invoices.store') }}" method="POST">
      @csrf
      @include('invoices._form', ['invoice' => null])
      <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">Enregistrer la facture</button>
      </div>
    </form>
  </div>
</div>
@endsection
