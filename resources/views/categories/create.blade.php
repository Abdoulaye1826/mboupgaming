@extends('layouts.dashboard')

@section('title', 'Nouvelle catégorie')
@section('page-title', 'Nouvelle catégorie')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-plus-circle me-2"></i>Nouvelle catégorie</h1>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
          @csrf
          @include('categories._form')
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
