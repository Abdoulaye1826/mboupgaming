@extends('layouts.dashboard')

@section('title', 'Rapports & Statistiques')
@section('page-title', 'Rapports & Statistiques')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1>Rapports & Statistiques</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rapports</li>
      </ol>
    </nav>
  </div>
  <div class="text-muted small">
    <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l d F Y') }}
  </div>
</div>

<div class="row g-3 mb-4">
  @php
    $reportKpis = [
      ['label' => 'CA du jour', 'value' => number_format($stats['revenue_today'], 0, ',', ' ') . ' FCFA', 'icon' => 'bi-currency-exchange', 'color' => 'bg-primary bg-opacity-10 text-primary'],
      ['label' => 'CA du mois', 'value' => number_format($stats['revenue_month'], 0, ',', ' ') . ' FCFA', 'icon' => 'bi-graph-up-arrow', 'color' => 'bg-success bg-opacity-10 text-success'],
      ['label' => 'Ventes validées', 'value' => $stats['sales_count'], 'icon' => 'bi-cart-check', 'color' => 'bg-info bg-opacity-10 text-info'],
      ['label' => 'Factures émises', 'value' => $stats['invoices_count'], 'icon' => 'bi-file-earmark-text', 'color' => 'bg-secondary bg-opacity-10 text-secondary'],
      ['label' => 'Factures payées', 'value' => $stats['paid_invoices_count'], 'icon' => 'bi-wallet2', 'color' => 'bg-success bg-opacity-10 text-success'],
      ['label' => 'Impayés', 'value' => $stats['pending_invoices_count'], 'icon' => 'bi-hourglass-split', 'color' => 'bg-warning bg-opacity-10 text-warning'],
      ['label' => 'Nouveaux clients (mois)', 'value' => $stats['new_customers_month'], 'icon' => 'bi-person-plus', 'color' => 'bg-primary bg-opacity-10 text-primary'],
      ['label' => 'Clients totaux', 'value' => $stats['customers_count'], 'icon' => 'bi-people', 'color' => 'bg-info bg-opacity-10 text-info'],
    ];
  @endphp

  @foreach($reportKpis as $kpi)
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

<div class="row g-3 mb-4">
  <div class="col-lg-4">
    <div class="chart-card h-100">
      <div class="card-title"><i class="bi bi-pie-chart me-2"></i>Statut des factures</div>
      <canvas id="invoiceStatusChart" height="260"></canvas>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="chart-card h-100">
      <div class="card-title"><i class="bi bi-bar-chart me-2"></i>Chiffre d'affaires par mois</div>
      <canvas id="salesByMonthChart" height="260"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="chart-card h-100">
      <div class="card-title"><i class="bi bi-bar-chart-line me-2"></i>Ventes par catégorie</div>
      <canvas id="salesByCategoryChart" height="260"></canvas>
    </div>
  </div>
  <div class="col-lg-6">
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
              <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->customer?->full_name ?? '—' }}</td>
                <td class="text-end">{{ number_format($invoice->total_ttc, 0, ',', ' ') }} FCFA</td>
                <td class="text-end">
                  <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'issued' ? 'bg-warning text-dark' : 'bg-danger') }}">
                    {{ App\Enums\InvoiceStatus::from($invoice->status)->label() }}
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

<div class="row g-3">
  <div class="col-lg-6">
    <div class="table-card h-100">
      <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-trophy me-2"></i>Top clients</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Client</th>
              <th class="text-center">Factures</th>
              <th class="text-end">Montant</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topCustomers as $index => $customer)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $customer->full_name }}</td>
                <td class="text-center"><span class="badge bg-primary">{{ $customer->invoices_count }}</span></td>
                <td class="text-end">{{ number_format($customer->total_amount, 0, ',', ' ') }} FCFA</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Aucun client n’a encore passé de commande</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
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
                <td colspan="4" class="text-center text-muted py-4">Aucun vendeur enregistré</td>
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

  new Chart(document.getElementById('salesByMonthChart'), {
    type: 'bar',
    data: {
      labels: @json($salesByMonth['labels']),
      datasets: [{
        label: 'CA (FCFA)',
        data: @json($salesByMonth['data']),
        backgroundColor: 'rgba(59, 130, 246, 0.8)',
        borderRadius: 10,
      }]
    },
    options: {
      ...chartDefaults,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  const categoryLabels = @json($salesByCategory['labels']);
  const categoryData = @json($salesByCategory['data']);
  const categoryColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899'];

  new Chart(document.getElementById('salesByCategoryChart'), {
    type: 'doughnut',
    data: {
      labels: categoryLabels.length ? categoryLabels : ['Aucune donnée'],
      datasets: [{
        data: categoryData.length ? categoryData : [1],
        backgroundColor: categoryLabels.length ? categoryColors.slice(0, categoryLabels.length) : ['#e2e8f0'],
      }]
    },
    options: {
      ...chartDefaults,
      plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
  });

  const invoiceLabels = @json($invoiceStatusSummary['labels']);
  const invoiceData = @json($invoiceStatusSummary['values']);
  const invoiceColors = ['#0d6efd','#198754','#ffc107','#dc3545'];

  new Chart(document.getElementById('invoiceStatusChart'), {
    type: 'doughnut',
    data: {
      labels: invoiceLabels.length ? invoiceLabels : ['Aucune donnée'],
      datasets: [{
        data: invoiceData.length ? invoiceData : [1],
        backgroundColor: invoiceLabels.length ? invoiceColors.slice(0, invoiceLabels.length) : ['#e2e8f0'],
      }]
    },
    options: {
      ...chartDefaults,
      plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
  });
</script>
@endpush
