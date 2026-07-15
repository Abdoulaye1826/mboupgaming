{{-- Tableau de bord simplifié à l'essentiel : un seul KPI principal (chiffre
     d'affaires) mis en avant, et deux KPI secondaires (nombre de ventes,
     montant encaissé). Le détail complet (facturation, panier moyen, marge,
     devis, valeur du stock, alertes...) reste disponible plus bas sur cette
     page (tableaux) et sur la page Rapports. Rechargée entièrement
     (innerHTML) à chaque changement de période. --}}
@php
  $p = $stats['period'];
@endphp

<div class="row g-3 mb-3">
  <div class="col-12">
    <div class="kpi-card kpi-card--hero">
      <div class="kpi-hero__top">
        <div class="kpi-hero__main">
          <div class="kpi-hero__icon"><i class="bi bi-currency-exchange"></i></div>
          <div>
            <div class="kpi-hero__label">Chiffre d'affaires</div>
            <div class="kpi-hero__value">{{ number_format($p['revenue'], 0, ',', ' ') }} <span>FCFA</span></div>
          </div>
        </div>

        <div class="kpi-hero__filter">
          <select id="periodSelect" class="kpi-hero__select">
            <option value="today" @selected($period->key === 'today')>Aujourd'hui</option>
            <option value="yesterday" @selected($period->key === 'yesterday')>Hier</option>
            <option value="week" @selected($period->key === 'week')>Cette semaine</option>
            <option value="month" @selected($period->key === 'month')>Ce mois</option>
            <option value="year" @selected($period->key === 'year')>Cette année</option>
            <option value="custom" @selected($period->key === 'custom')>Période personnalisée</option>
          </select>

          <div id="customPeriodFields" class="kpi-hero__custom @if($period->key !== 'custom') d-none @endif">
            <input type="date" id="periodStart" class="kpi-hero__date" value="{{ $period->key === 'custom' ? $period->start->toDateString() : '' }}">
            <input type="date" id="periodEnd" class="kpi-hero__date" value="{{ $period->key === 'custom' ? $period->end->toDateString() : '' }}">
            <button type="button" id="applyCustomPeriod" class="kpi-hero__apply">
              <i class="bi bi-check-lg"></i>
            </button>
          </div>

          <div class="kpi-hero__period-label" id="periodLabel">
            <i class="bi bi-calendar3 me-1"></i><span>{{ $period->label }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-6">
    <div class="kpi-card">
      <div class="d-flex align-items-center gap-3">
        <div class="kpi-icon bg-info text-info">
          <i class="bi bi-cart-check"></i>
        </div>
        <div>
          <div class="kpi-label">Nombre de ventes</div>
          <div class="kpi-value">{{ $p['sales_count'] }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="kpi-card">
      <div class="d-flex align-items-center gap-3">
        <div class="kpi-icon bg-success text-success">
          <i class="bi bi-cash-stack"></i>
        </div>
        <div>
          <div class="kpi-label">Montant encaissé</div>
          <div class="kpi-value">{{ number_format($p['amount_paid'], 0, ',', ' ') }} FCFA</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="text-end mb-2">
  <a href="{{ route('reports.index') }}" class="small text-decoration-none">
    Voir le rapport complet <i class="bi bi-arrow-right"></i>
  </a>
</div>
