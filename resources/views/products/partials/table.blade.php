<tbody>
  @forelse($products as $product)
    <tr>
      <td>
        @if($product->image)
          <img src="{{ asset('storage/'.$product->image) }}" alt="" class="rounded" loading="lazy"
               style="width:92px;height:92px;object-fit:cover">
        @else
          <div class="bg-light rounded d-flex align-items-center justify-content-center"
               style="width:92px;height:92px">
            <i class="bi bi-controller text-muted fs-2"></i>
          </div>
        @endif
      </td>
      <td><code>{{ $product->reference }}</code></td>
      <td>
        <a href="{{ route('products.show', $product) }}" class="text-decoration-none fw-medium">
          {{ $product->name }}
        </a>
        @if($product->brand)
          <br><small class="text-muted">{{ $product->brand }}</small>
        @endif
      </td>
      <td><span class="badge bg-light text-dark">{{ $product->category?->name }}</span></td>
      <td class="text-end">{{ number_format($product->sale_price, 0, ',', ' ') }}</td>
      <td class="text-center">
        <span class="fw-semibold {{ $product->isOutOfStock() ? 'text-danger' : ($product->isLowStock() ? 'text-warning' : '') }}">
          {{ $product->stock_quantity }}
        </span>
      </td>
      <td>
        @if($product->isOutOfStock())
          <span class="badge bg-danger">Rupture</span>
        @elseif($product->isLowStock())
          <span class="badge bg-warning text-dark">Faible</span>
        @else
          <span class="badge bg-success">OK</span>
        @endif
      </td>
      <td>
        @if($product->is_active)
          <span class="badge bg-success">Actif</span>
        @else
          <span class="badge bg-secondary">Inactif</span>
        @endif
      </td>
      <td class="text-end text-nowrap">
        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
          <i class="bi bi-eye"></i>
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
          <i class="bi bi-pencil"></i>
        </a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Supprimer ce produit ?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="9" class="text-center text-muted py-4">Aucun produit trouvé</td>
    </tr>
  @endforelse
</tbody>
