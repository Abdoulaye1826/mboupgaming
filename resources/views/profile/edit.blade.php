@extends('layouts.dashboard')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="page-header">
  <h1>Mon profil</h1>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Informations personnelles</div>
      <div class="card-body">
        @include('profile.partials.update-profile-information-form')
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">Modifier le mot de passe</div>
      <div class="card-body">
        @include('profile.partials.update-password-form')
      </div>
    </div>

    <div class="card border-0 shadow-sm border-danger">
      <div class="card-header bg-white fw-semibold text-danger">Supprimer le compte</div>
      <div class="card-body">
        @include('profile.partials.delete-user-form')
      </div>
    </div>
  </div>
</div>
@endsection
