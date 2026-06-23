@extends('layouts.dashboard')

@section('title', 'Nouveau produit')
@section('page-title', 'Nouveau produit')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-plus-circle me-2"></i>Nouveau produit</h1>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
      @csrf
      @include('products._form', ['categories' => $categories])
      <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
