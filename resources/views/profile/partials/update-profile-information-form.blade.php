<form method="post" action="{{ route('profile.update') }}">
  @csrf
  @method('patch')

  <div class="mb-3">
    <label for="name" class="form-label">Nom</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror"
           id="name" name="name" value="{{ old('name', $user->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email" value="{{ old('email', $user->email) }}" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">
    <i class="bi bi-check-lg me-1"></i>Enregistrer
  </button>

  @if (session('status') === 'profile-updated')
    <span class="text-success small ms-2">Profil mis à jour.</span>
  @endif
</form>
