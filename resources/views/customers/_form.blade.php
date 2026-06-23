<div class="row">
  <div class="col-md-6 mb-3">
    <label for="full_name" class="form-label">Nom complet <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
           id="full_name" name="full_name" value="{{ old('full_name', $customer->full_name ?? '') }}" required>
    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 mb-3">
    <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror"
           id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required>
    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email" value="{{ old('email', $customer->email ?? '') }}">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 mb-3">
    <label for="city" class="form-label">Ville</label>
    <input type="text" class="form-control @error('city') is-invalid @enderror"
           id="city" name="city" value="{{ old('city', $customer->city ?? '') }}">
    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="mb-3">
  <label for="address" class="form-label">Adresse</label>
  <textarea class="form-control @error('address') is-invalid @enderror"
            id="address" name="address" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label for="registered_at" class="form-label">Date d'inscription <span class="text-danger">*</span></label>
    <input type="date" class="form-control @error('registered_at') is-invalid @enderror"
           id="registered_at" name="registered_at" value="{{ old('registered_at', optional($customer->registered_at)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    @error('registered_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
