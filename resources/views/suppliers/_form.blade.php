<div class="mb-3">
  <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
  <input type="text" class="form-control @error('name') is-invalid @enderror"
         id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required>
  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3">
  <div class="col-md-6">
    <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror"
           id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" required>
    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="mb-3">
  <label for="address" class="form-label">Adresse</label>
  <textarea class="form-control @error('address') is-invalid @enderror"
            id="address" name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 align-items-center">
  <div class="col-md-6">
    <label for="country" class="form-label">Pays</label>
    <input type="text" class="form-control @error('country') is-invalid @enderror"
           id="country" name="country" value="{{ old('country', $supplier->country ?? 'Sénégal') }}">
    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <div class="form-check form-switch mt-4">
      <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
             {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Fournisseur actif</label>
    </div>
  </div>
</div>
