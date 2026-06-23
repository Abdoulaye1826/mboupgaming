<p class="text-muted small mb-3">
  La suppression de votre compte est définitive. Toutes vos données seront effacées.
</p>

<button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
  <i class="bi bi-trash me-1"></i>Supprimer mon compte
</button>

<div class="modal fade" id="deleteAccountModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <div class="modal-header">
          <h5 class="modal-title">Confirmer la suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Entrez votre mot de passe pour confirmer la suppression de votre compte.</p>
          <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                 name="password" placeholder="Mot de passe" required>
          @error('password', 'userDeletion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
        </div>
      </form>
    </div>
  </div>
</div>

@if($errors->userDeletion->isNotEmpty())
  @push('scripts')
  <script>
    new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
  </script>
  @endpush
@endif
