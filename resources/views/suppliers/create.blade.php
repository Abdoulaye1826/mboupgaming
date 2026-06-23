@extends('layouts.dashboard')

@section('title', 'Nouveau fournisseur')
@section('page-title', 'Ajouter un fournisseur')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-plus-lg me-2"></i>Ajouter un fournisseur</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Fournisseurs</a></li>
        <li class="breadcrumb-item active">Ajouter</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Retour</a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="{{ route('suppliers.store') }}" method="POST">
      @csrf
      @include('suppliers._form')
      <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
  </div>
</div>
@endsection
