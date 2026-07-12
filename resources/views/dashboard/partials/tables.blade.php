{{-- Tableaux "activité récente" / classements du tableau de bord.
     Le détail complet (mouvements de stock, devis récents, top clients,
     statut des factures, CA par mois) est sur la page Rapports — ici on ne
     garde que l'essentiel de l'activité courante. --}}

{{-- Factures récentes --}}
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="table-card h-100">
      <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Factures récentes</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Numéro</th>
              <th>Client</th>
              <th class="text-end">Montant</th>
              <th class="text-end">Statut</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentInvoices as $invoice)
              @php
                $invoiceStatus = $invoice->status instanceof App\Enums\InvoiceStatus
                    ? $invoice->status
                    : App\Enums\InvoiceStatus::from($invoice->status);
              @endphp
              <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->customer?->full_name ?? '—' }}</td>
                <td class="text-end">{{ number_format($invoice->total_ttc, 0, ',', ' ') }} FCFA</td>
                <td class="text-end">
                  <span class="badge {{ $invoiceStatus === App\Enums\InvoiceStatus::Paid ? 'bg-success' : ($invoiceStatus === App\Enums\InvoiceStatus::Issued ? 'bg-warning text-dark' : 'bg-danger') }}">
                    {{ $invoiceStatus->label() }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Aucune facture récente</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Vendeurs performants (borné à la période) --}}
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="table-card h-100">
      <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-people-fill me-2"></i>Vendeurs performants</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Vendeur</th>
              <th class="text-center">Ventes</th>
              <th class="text-end">Montant</th>
            </tr>
          </thead>
          <tbody>
            @forelse($salesByUser as $index => $user)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td class="text-center"><span class="badge bg-info">{{ $user->sales_count }}</span></td>
                <td class="text-end">{{ number_format($user->total_amount, 0, ',', ' ') }} FCFA</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Aucun vendeur enregistré sur cette période</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Top produits + Alertes stock — masqués pour le caissier --}}
@unless($isCashier)
<div class="row g-3">
  <div class="col-lg-7">
    <div class="table-card">
      <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-trophy me-2"></i>Produits les plus vendus</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Produit</th>
              <th class="text-center">Qté vendue</th>
              <th class="text-end">Montant</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topProducts as $index => $product)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center"><span class="badge bg-primary">{{ $product->total_qty }}</span></td>
                <td class="text-end">{{ number_format($product->total_amount, 0, ',', ' ') }} FCFA</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Aucune vente enregistrée sur cette période</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="table-card">
      <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-exclamation-triangle me-2"></i>Alertes stock</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Produit</th>
              <th class="text-center">Stock</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            @forelse($stockAlerts as $product)
              <tr>
                <td>
                  <div class="fw-medium">{{ $product->name }}</div>
                  <small class="text-muted">{{ $product->category?->name }}</small>
                </td>
                <td class="text-center">{{ $product->stock_quantity }}</td>
                <td>
                  @if($product->isOutOfStock())
                    <span class="badge bg-danger">Rupture</span>
                  @else
                    <span class="badge bg-warning text-dark">Stock faible</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-4">Aucune alerte stock</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endunless
