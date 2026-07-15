{{-- Couleurs pilotées par les paramètres de l'entreprise (Paramètres →
     Couleurs). Injectées après dashboard.css pour le surcharger, sans
     toucher au fichier CSS lui-même.
     Couleur primaire = couleur dominante (menu latéral, KPI principal,
     barre de défilement) ; couleur secondaire = accent (boutons, liens). --}}
@php
  $e = entreprise();
  $primary = $e->couleur_primaire ?: '#1432CA';
  $secondary = $e->couleur_secondaire ?: '#153BFF';
  $secondaryDark = $e->darken($secondary);
  $secondaryRgb = $e->rgb($secondary);
@endphp
<style>
  :root {
    --copper: {{ $secondary }};
    --copper-dark: {{ $secondaryDark }};
    --copper-soft: rgba({{ $secondaryRgb }}, .14);
  }
  .sidebar { background: {{ $primary }}; }
  .kpi-card--hero { background: linear-gradient(135deg, {{ $primary }} 0%, {{ $secondary }} 100%); }
  ::-webkit-scrollbar-thumb { background: {{ $primary }}; }
</style>
