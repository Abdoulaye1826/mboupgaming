@extends('layouts.dashboard')

@section('title', 'Modifier produit')
@section('page-title', 'Modifier produit')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-pencil me-2"></i>Modifier : {{ $product->name }}</h1>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
      @csrf @method('PUT')
      @include('products._form', ['product' => $product, 'categories' => $categories])
      <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
