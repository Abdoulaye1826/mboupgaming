<div class="row">
  <div class="col-md-6 mb-3">
    <label for="customer_id" class="form-label">Client</label>
    <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
      <option value="">— Client anonyme —</option>
      @foreach($customers as $customer)
        <option value="{{ $customer->id }}" @selected(old('customer_id', $sale?->customer_id ?? '') == $customer->id)>
          {{ $customer->full_name }}
        </option>
      @endforeach
    </select>
    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6 mb-3">
    <label for="sale_date" class="form-label">Date de vente <span class="text-danger">*</span></label>
    <input type="date" class="form-control @error('sale_date') is-invalid @enderror"
           id="sale_date" name="sale_date" value="{{ old('sale_date', $sale?->sale_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    @error('sale_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label for="discount_amount" class="form-label">Remise (FCFA)</label>
    <input type="number" step="0.01" min="0" class="form-control @error('discount_amount') is-invalid @enderror"
           id="discount_amount" name="discount_amount" value="{{ old('discount_amount', $sale?->discount_amount ?? 0) }}">
    @error('discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="tax_rate" class="form-label">TVA (%)</label>
    <input type="number" step="0.01" min="0" class="form-control @error('tax_rate') is-invalid @enderror"
           id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $sale?->tax_rate ?? 18) }}">
    @error('tax_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
      <option value="draft" @selected(old('status', $sale?->status->value ?? 'draft') === 'draft')>Brouillon</option>
      <option value="validated" @selected(old('status', $sale?->status->value ?? '') === 'validated')>Validée</option>
      <option value="cancelled" @selected(old('status', $sale?->status->value ?? '') === 'cancelled')>Annulée</option>
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label for="subtotal_ht" class="form-label">Sous-total HT <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" class="form-control @error('subtotal_ht') is-invalid @enderror"
           id="subtotal_ht" name="subtotal_ht" value="{{ old('subtotal_ht', $sale?->subtotal_ht ?? 0) }}" required>
    @error('subtotal_ht')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="tax_amount" class="form-label">Montant TVA <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" class="form-control @error('tax_amount') is-invalid @enderror"
           id="tax_amount" name="tax_amount" value="{{ old('tax_amount', $sale?->tax_amount ?? 0) }}" required>
    @error('tax_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="total_ttc" class="form-label">Total TTC <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" class="form-control @error('total_ttc') is-invalid @enderror"
           id="total_ttc" name="total_ttc" value="{{ old('total_ttc', $sale?->total_ttc ?? 0) }}" required>
    @error('total_ttc')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="mb-3">
  <label for="notes" class="form-label">Notes</label>
  <textarea class="form-control @error('notes') is-invalid @enderror"
            id="notes" name="notes" rows="3">{{ old('notes', $sale->notes ?? '') }}</textarea>
  @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
