<form method="post" action="{{ route('password.update') }}">
  @csrf
  @method('put')

  <div class="mb-3">
    <label for="current_password" class="form-label">Mot de passe actuel</label>
    <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
           id="current_password" name="current_password">
    @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Nouveau mot de passe</label>
    <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
           id="password" name="password">
    @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
  </div>

  <button type="submit" class="btn btn-primary">
    <i class="bi bi-shield-lock me-1"></i>Mettre à jour
  </button>

  @if (session('status') === 'password-updated')
    <span class="text-success small ms-2">Mot de passe mis à jour.</span>
  @endif
</form>
