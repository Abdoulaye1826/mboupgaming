@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
<p class="text-muted small mb-4">
  Entrez votre adresse email pour recevoir un lien de réinitialisation.
</p>

<form method="POST" action="{{ route('password.email') }}">
  @csrf

  <div class="mb-4">
    <label for="email" class="form-label fw-medium">Adresse email</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-envelope"></i></span>
      <input type="email" class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>
    @error('email')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary w-100 py-2">
  <i class="bi bi-send me-2"></i>Envoyer le lien
  </button>

  <div class="text-center mt-3">
    <a href="{{ route('login') }}" class="small text-decoration-none">
      <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
    </a>
  </div>
</form>
@endsection
