<div class="table-card">
  <div class="p-3 border-bottom fw-semibold">Derniers mouvements de stock</div>
  <div class="table-responsive">
    <table class="table table-sm table-hover mb-0">
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Qté</th>
          <th>Avant → Après</th>
          <th>Motif</th>
        </tr>
      </thead>
      <tbody>
        @forelse($product->stockMovements as $movement)
          <tr>
            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
            <td><span class="badge bg-light text-dark">{{ $movement->type->label() }}</span></td>
            <td>{{ $movement->quantity }}</td>
            <td>{{ $movement->quantity_before }} → {{ $movement->quantity_after }}</td>
            <td>{{ $movement->reason ?? '—' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Aucun mouvement de stock trouvé.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
