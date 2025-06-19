<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fa-solid fa-industry me-2 text-warning"></i>
            {{ $title ?? 'IndustriEko' }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarIndustrial">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarIndustrial">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">
                        <i class="fa-solid fa-map-location-dot"></i> Industry Map
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('table') ? 'active' : '' }}" href="{{ route('table') }}">
                            <i class="fa-solid fa-table-list"></i> Data Table
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-layer-group"></i> GIS Data
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark border-0">
                            <li><a class="dropdown-item text-light" href="{{ route('api.points') }}" target="_blank">📍 Points</a></li>
                            <li><a class="dropdown-item text-light" href="{{ route('api.polylines') }}" target="_blank">📏 Polylines</a></li>
                            <li><a class="dropdown-item text-light" href="{{ route('api.polygons') }}" target="_blank">🗺️ Polygons</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn nav-link text-danger border-0">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link text-info" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
