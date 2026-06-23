<div class="row">
  <div class="col-md-8 mb-3">
    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror"
           id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="category_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
      <option value="">— Sélectionner —</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>
    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="supplier_id" class="form-label">Fournisseur</label>
    <select id="supplier_id" name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
      <option value="">— Aucun —</option>
      @foreach($suppliers as $supplier)
        <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>
          {{ $supplier->name }}
        </option>
      @endforeach
    </select>
    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label for="reference" class="form-label">Référence <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('reference') is-invalid @enderror"
           id="reference" name="reference" value="{{ old('reference', $product->reference ?? '') }}" required>
    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="barcode" class="form-label">Code-barres</label>
    <input type="text" class="form-control @error('barcode') is-invalid @enderror"
           id="barcode" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}">
    @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4 mb-3">
    <label for="brand" class="form-label">Marque</label>
    <input type="text" class="form-control @error('brand') is-invalid @enderror"
           id="brand" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
           placeholder="Sony, Nintendo, Microsoft...">
    @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="mb-3">
  <label for="description" class="form-label">Description</label>
  <textarea class="form-control @error('description') is-invalid @enderror"
            id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
  @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
  <div class="col-md-3 mb-3">
    <label for="purchase_price" class="form-label">Prix achat (FCFA) <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" class="form-control @error('purchase_price') is-invalid @enderror"
           id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" required>
    @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3 mb-3">
    <label for="sale_price" class="form-label">Prix vente (FCFA) <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror"
           id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" required>
    @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3 mb-3">
    <label for="stock_quantity" class="form-label">Stock <span class="text-danger">*</span></label>
    <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror"
           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
    @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3 mb-3">
    <label for="minimum_stock" class="form-label">Stock minimum <span class="text-danger">*</span></label>
    <input type="number" min="0" class="form-control @error('minimum_stock') is-invalid @enderror"
           id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock ?? 5) }}" required>
    @error('minimum_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label for="image" class="form-label">Image produit</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror"
           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp">
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if(isset($product) && $product->image)
      <div class="mt-2 d-flex align-items-center gap-3">
        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
             class="rounded" style="width:80px;height:80px;object-fit:cover">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
          <label class="form-check-label text-danger small" for="remove_image">Supprimer l'image</label>
        </div>
      </div>
    @endif
  </div>
  <div class="col-md-6 mb-3 d-flex align-items-end">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
             {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Produit actif (disponible à la vente)</label>
    </div>
  </div>
</div>
