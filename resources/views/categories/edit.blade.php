@extends('layouts.dashboard')

@section('title', 'Modifier catégorie')
@section('page-title', 'Modifier catégorie')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-pencil me-2"></i>Modifier : {{ $category->name }}</h1>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
          @csrf @method('PUT')
          @include('categories._form', ['category' => $category])
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
