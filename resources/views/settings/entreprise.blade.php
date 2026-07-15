@extends('layouts.dashboard')

@section('title', "Paramètres de l'entreprise")
@section('page-title', "Paramètres")

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1>Paramètres de l'entreprise</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Paramètres</li>
      </ol>
    </nav>
  </div>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('settings.entreprise.update') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card mb-4">
    <div class="card-header fw-bold">Identité</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom de l'entreprise</label>
          <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                 value="{{ old('nom', $entreprise->nom) }}" required>
          @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Sous-titre / slogan</label>
          <input type="text" name="slogan" class="form-control"
                 value="{{ old('slogan', $entreprise->slogan) }}" placeholder="Ex : Système d'information">
        </div>
        <div class="col-md-6">
          <label class="form-label">Logo actuel</label>
          <div class="d-flex align-items-center gap-3">
            <img src="{{ $entreprise->logo_url }}" alt="{{ $entreprise->nom }}"
                 style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                   accept="image/png,image/jpeg,image/webp">
          </div>
          @error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          <small class="text-muted">PNG, JPG ou WEBP — 2 Mo maximum.</small>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Couleurs</div>
    <div class="card-body">
      <p class="text-muted small mb-3">Ces deux couleurs pilotent l'ensemble de l'habillage de l'application (menu latéral, boutons, KPI principal du tableau de bord) — sans toucher au code.</p>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Couleur primaire</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" name="couleur_primaire" class="form-control form-control-color"
                   value="{{ old('couleur_primaire', $entreprise->couleur_primaire ?? '#1432CA') }}">
            <span class="text-muted small">Menu latéral, KPI principal, barre de défilement</span>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Couleur secondaire</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" name="couleur_secondaire" class="form-control form-control-color"
                   value="{{ old('couleur_secondaire', $entreprise->couleur_secondaire ?? '#153BFF') }}">
            <span class="text-muted small">Boutons, liens, accents</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Contact</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Téléphone</label>
          <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $entreprise->telephone) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $entreprise->whatsapp) }}" placeholder="221781928588">
        </div>
        <div class="col-md-4">
          <label class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $entreprise->email) }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Adresse</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Adresse — ligne 1</label>
          <input type="text" name="adresse_ligne1" class="form-control" value="{{ old('adresse_ligne1', $entreprise->adresse_ligne1) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Adresse — ligne 2</label>
          <input type="text" name="adresse_ligne2" class="form-control" value="{{ old('adresse_ligne2', $entreprise->adresse_ligne2) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Ville</label>
          <input type="text" name="ville" class="form-control" value="{{ old('ville', $entreprise->ville) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Pays</label>
          <input type="text" name="pays" class="form-control" value="{{ old('pays', $entreprise->pays ?? 'Sénégal') }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Informations légales</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">NINEA</label>
          <input type="text" name="ninea" class="form-control" value="{{ old('ninea', $entreprise->ninea) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">RCCM</label>
          <input type="text" name="rccm" class="form-control" value="{{ old('rccm', $entreprise->rccm) }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Documents (factures / devis)</div>
    <div class="card-body">
      <label class="form-label">Conditions de vente</label>
      <textarea name="conditions_vente" rows="3" class="form-control"
                placeholder="Le service après-vente peut durer une semaine maximum si la garantie n'est pas expirée. Nous ne remboursons pas — nous réparons ou remplaçons.">{{ old('conditions_vente', $entreprise->conditions_vente) }}</textarea>
      <small class="text-muted">Affichées telles quelles sur chaque facture et devis. Laisser vide pour garder le texte par défaut.</small>
    </div>
  </div>

  <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
</form>
@endsection
