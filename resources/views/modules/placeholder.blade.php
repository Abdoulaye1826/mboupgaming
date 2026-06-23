@extends('layouts.dashboard')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="page-header">
  <h1><i class="bi {{ $icon }} me-2"></i>{{ $title }}</h1>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body text-center py-5">
    <div class="mb-3">
      <i class="bi {{ $icon }} text-primary" style="font-size:3rem"></i>
    </div>
    <h5 class="text-muted">Module en cours de développement</h5>
    <p class="text-muted mb-0">Ce module sera disponible dans la prochaine phase.</p>
  </div>
</div>
@endsection
