<header class="top-navbar">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-link text-dark d-lg-none p-0" id="sidebarToggle">
      <i class="bi bi-list fs-4"></i>
    </button>
    <div>
      <h6 class="mb-0 fw-semibold">@yield('page-title', 'Tableau de bord')</h6>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    {{-- Notifications --}}
    <div class="dropdown">
      <button class="btn btn-link text-secondary position-relative" data-bs-toggle="dropdown">
        <i class="bi bi-bell fs-5"></i>
        @if(($unreadCount ?? 0) > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem">
            {{ $unreadCount }}
          </span>
        @endif
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" style="width:320px">
        <li class="d-flex align-items-center justify-content-between px-3">
          <h6 class="dropdown-header px-0">Notifications</h6>
          @if(($unreadCount ?? 0) > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="mb-2">
              @csrf
              <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">Tout marquer comme lu</button>
            </form>
          @endif
        </li>
        @forelse(($recentNotifications ?? []) as $notification)
          <li>
            <a href="{{ route('notifications.read', $notification) }}"
               class="dropdown-item d-flex gap-2 align-items-start py-2 {{ $notification->isRead() ? '' : 'bg-light' }}">
              <i class="bi {{ $notification->type?->value === 'out_of_stock' ? 'bi-exclamation-octagon text-danger' : 'bi-exclamation-triangle text-warning' }} mt-1"></i>
              <span>
                <span class="d-block fw-semibold small">{{ $notification->title }}</span>
                <span class="d-block text-muted small">{{ $notification->message }}</span>
                <span class="d-block text-muted" style="font-size:0.7rem">{{ $notification->created_at->diffForHumans() }}</span>
              </span>
            </a>
          </li>
        @empty
          <li><span class="dropdown-item-text text-muted small">Aucune notification</span></li>
        @endforelse
      </ul>
    </div>

    {{-- Profil utilisateur --}}
    <div class="dropdown">
      <button class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
              data-bs-toggle="dropdown">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
             style="width:36px;height:36px;font-size:0.85rem;font-weight:600">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="d-none d-md-block text-start">
          <div class="fw-semibold" style="font-size:0.85rem">{{ auth()->user()->name }}</div>
          <div class="text-muted" style="font-size:0.7rem">{{ auth()->user()->role?->name }}</div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow">
        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
              <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>
