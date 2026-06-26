<div data-form-sections>

  <div class="form-section">
    <button type="button" class="form-section__header" data-toggle-section aria-expanded="true" aria-controls="section-identite-contact">
      <span class="form-section__title"><i class="bi bi-building"></i>Identité &amp; contact</span>
      <i class="bi bi-chevron-down chevron"></i>
    </button>
    <div class="form-section__body" id="section-identite-contact">
      <div class="field-group">
        <label for="name" class="form-label">Nom du fournisseur <span class="req">*</span></label>
        <div class="field-input-wrap">
          <i class="bi bi-building field-icon"></i>
          <input type="text" class="form-control has-icon @error('name') is-invalid @enderror"
                 id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}"
                 placeholder="Ex : Sony Distribution Sénégal" required>
          <i class="bi bi-check-circle-fill valid-feedback-icon"></i>
          <i class="bi bi-exclamation-circle-fill invalid-feedback-icon"></i>
        </div>
        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 field-group">
          <label for="phone" class="form-label">Téléphone <span class="req">*</span></label>
          <div class="field-input-wrap">
            <i class="bi bi-telephone field-icon"></i>
            <input type="text" class="form-control has-icon @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"
                   placeholder="+221 77 123 45 67" required>
          </div>
          @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 field-group mb-0">
          <label for="email" class="form-label">Email</label>
          <div class="field-input-wrap">
            <i class="bi bi-envelope field-icon"></i>
            <input type="email" class="form-control has-icon @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
                   placeholder="contact@fournisseur.com">
          </div>
          @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>

  <div class="form-section">
    <button type="button" class="form-section__header" data-toggle-section aria-expanded="true" aria-controls="section-adresse">
      <span class="form-section__title"><i class="bi bi-geo-alt"></i>Adresse &amp; statut</span>
      <i class="bi bi-chevron-down chevron"></i>
    </button>
    <div class="form-section__body" id="section-adresse">
      <div class="field-group">
        <label for="address" class="form-label">Adresse</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="3"
                  placeholder="Quartier, rue, repère...">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row align-items-center mb-0">
        <div class="col-md-6 field-group mb-md-0">
          <label for="country" class="form-label">Pays</label>
          <div class="field-input-wrap">
            <i class="bi bi-flag field-icon"></i>
            <input type="text" class="form-control has-icon @error('country') is-invalid @enderror"
                   id="country" name="country" value="{{ old('country', $supplier->country ?? 'Sénégal') }}">
          </div>
          @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 field-group mb-0">
          <label class="form-label">Disponibilité</label>
          <div class="form-check form-switch fs-6 ps-1">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" role="switch"
                   {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Fournisseur actif</label>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
