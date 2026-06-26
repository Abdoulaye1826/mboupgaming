<div class="field-group">
  <label for="name" class="form-label">Nom <span class="req">*</span></label>
  <div class="field-input-wrap">
    <i class="bi bi-bookmark field-icon"></i>
    <input type="text" class="form-control has-icon @error('name') is-invalid @enderror"
           id="name" name="name" value="{{ old('name', $category->name ?? '') }}"
           placeholder="Ex : Consoles, Accessoires, Jeux vidéo..." required>
    <i class="bi bi-check-circle-fill valid-feedback-icon"></i>
    <i class="bi bi-exclamation-circle-fill invalid-feedback-icon"></i>
  </div>
  @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="field-group">
  <label for="description" class="form-label">Description</label>
  <textarea class="form-control @error('description') is-invalid @enderror"
            id="description" name="description" rows="3"
            placeholder="Quelques mots pour décrire cette catégorie...">{{ old('description', $category->description ?? '') }}</textarea>
  @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="field-group mb-0">
  <label class="form-label">Disponibilité</label>
  <div class="form-check form-switch fs-6 ps-1">
    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" role="switch"
           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Catégorie active</label>
  </div>
  <div class="form-text">Désactivez-la pour la masquer des formulaires sans supprimer les produits associés.</div>
</div>
