@extends('layouts.dashboard')

@section('title', 'Modifier client')
@section('page-title', 'Modifier client')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-pencil me-2"></i>Modifier client : {{ $customer->full_name }}</h1>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('customers.update', $customer) }}">
      @csrf @method('PUT')
      @include('customers._form')
      <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
