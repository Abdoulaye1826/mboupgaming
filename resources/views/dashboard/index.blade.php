@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1>Tableau de bord</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Accueil</li>
      </ol>
    </nav>
  </div>
  <div class="text-muted small">
    <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l d F Y') }}
  </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
  @php
    $kpis = [
      ['label' => 'CA du jour', 'value' => number_format($stats['revenue_today'], 0, ',', ' ') . ' FCFA', 'icon' => 'bi-currency-exchange', 'color' => 'bg-primary bg-opacity-10 text-primary'],
      ['label' => 'CA du mois', 'value' => number_format($stats['revenue_month'], 0, ',', ' ') . ' FCFA', 'icon' => 'bi-graph-up-arrow', 'color' => 'bg-success bg-opacity-10 text-success'],
      ['label' => 'Ventes', 'value' => $stats['sales_count'], 'icon' => 'bi-cart-check', 'color' => 'bg-info bg-opacity-10 text-info'],
      ['label' => 'Produits', 'value' => $stats['products_count'], 'icon' => 'bi-controller', 'color' => 'bg-secondary bg-opacity-10 text-secondary'],
      ['label' => 'Ruptures', 'value' => $stats['out_of_stock_count'], 'icon' => 'bi-exclamation-octagon', 'color' => 'bg-danger bg-opacity-10 text-danger'],
      ['label' => 'Stock faible', 'value' => $stats['low_stock_count'], 'icon' => 'bi-exclamation-triangle', 'color' => 'bg-warning bg-opacity-10 text-warning'],
      ['label' => 'Clients', 'value' => $stats['customers_count'], 'icon' => 'bi-people', 'color' => 'bg-primary bg-opacity-10 text-primary'],
    ];
  @endphp

  @foreach($kpis as $kpi)
    <div class="col-6 col-md-4 col-xl-3">
      <div class="kpi-card">
        <div class="d-flex align-items-center gap-3">
          <div class="kpi-icon {{ $kpi['color'] }}">
            <i class="bi {{ $kpi['icon'] }}"></i>
          </div>
          <div>
            <div class="kpi-label">{{ $kpi['label'] }}</div>
            <div class="kpi-value">{{ $kpi['value'] }}</div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Graphiques --}}
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="chart-card">
      <div class="card-title"><i class="bi bi-bar-chart me-2"></i>Ventes par mois (FCFA)</div>
      <canvas id="salesByMonthChart" height="100"></canvas>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card">
      <div class="card-title"><i class="bi bi-pie-chart me-2"></i>Ventes par catégorie</div>
      <canvas id="salesByCategoryChart" height="200"></canvas>
    </div>
  </div>
</div>

<div class="row g-3">
  {{-- Top produits --}}
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
                <td colspan="4" class="text-center text-muted py-4">Aucune vente enregistrée pour le moment</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Alertes stock --}}
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  const chartDefaults = { responsive: true, maintainAspectRatio: true };

  // Ventes par mois
  new Chart(document.getElementById('salesByMonthChart'), {
    type: 'bar',
    data: {
      labels: @json($salesByMonth['labels']),
      datasets: [{
        label: 'CA (FCFA)',
        data: @json($salesByMonth['data']),
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderRadius: 6,
      }]
    },
    options: {
      ...chartDefaults,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Ventes par catégorie
  const catLabels = @json($salesByCategory['labels']);
  const catData = @json($salesByCategory['data']);
  const colors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899'];

  new Chart(document.getElementById('salesByCategoryChart'), {
    type: 'doughnut',
    data: {
      labels: catLabels.length ? catLabels : ['Aucune donnée'],
      datasets: [{
        data: catData.length ? catData : [1],
        backgroundColor: catLabels.length ? colors.slice(0, catLabels.length) : ['#e2e8f0'],
      }]
    },
    options: {
      ...chartDefaults,
      plugins: {
        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
      }
    }
  });
</script>
@endpush
