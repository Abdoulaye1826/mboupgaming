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

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
      @csrf
      @method('PUT')
      @include('suppliers._form')
      <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
  </div>
</div>
@endsection
