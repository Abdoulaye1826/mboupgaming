@extends('layouts.dashboard')

@section('title', 'Modifier fournisseur')
@section('page-title', 'Modifier fournisseur')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-pencil me-2"></i>Modifier fournisseur</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Fournisseurs</a></li>
        <li class="breadcrumb-item active">Modifier</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Retour</a>
</div>

<div class="form-shell u-animate">
  <form action="{{ route('suppliers.update', $supplier) }}" method="POST" data-ui-form novalidate>
    @csrf
    @method('PUT')
    <div class="form-card">
      <div class="form-card__header">
        <h2><i class="bi bi-truck"></i>Fiche fournisseur</h2>
        <p class="form-card__subtitle">Les champs marqués <span class="req">*</span> sont obligatoires.</p>
      </div>
      <div class="form-card__body">
        @include('suppliers._form')
      </div>
      <div class="form-card__footer">
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
      </div>
    </div>
  </form>
</div>
@endsection
