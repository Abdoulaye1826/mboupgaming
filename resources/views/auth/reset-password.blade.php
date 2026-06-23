@extends('layouts.guest')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<form method="POST" action="{{ route('password.store') }}">
  @csrf
  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <div class="mb-3">
    <label for="email" class="form-label fw-medium">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label for="password" class="form-label fw-medium">Nouveau mot de passe</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror"
           id="password" name="password" required>
    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-4">
    <label for="password_confirmation" class="form-label fw-medium">Confirmer le mot de passe</label>
    <input type="password" class="form-control" id="password_confirmation"
           name="password_confirmation" required>
  </div>

  <button type="submit" class="btn btn-primary w-100 py-2">
    <i class="bi bi-check-lg me-2"></i>Réinitialiser
  </button>
</form>
@endsection
