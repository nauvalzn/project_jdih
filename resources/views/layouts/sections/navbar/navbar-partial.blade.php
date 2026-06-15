@php
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
@endphp

<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme px-3"
    id="layout-navbar">

    <!-- Left side -->
    <div class="d-flex align-items-center me-auto mt-2 mb-2 mb-xl-0">
        <!-- Brand -->
        @auth
            <a href="{{ route('dashboard-analytics-pages') }}" class="navbar-brand me-4">
                <span class="fw-bold fs-5">Dashboard Management</span>
            </a>
        @else
            <a href="{{ url('/') }}" class="navbar-brand me-4">
                <span class="fw-bold fs-5">Document Portal</span>
            </a>
        @endauth

        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="d-flex" style="min-width: 280px;">
            <input type="text" name="q" class="form-control rounded-start" placeholder="Search..."
                value="{{ request('q') }}">
            <button class="btn btn-outline-secondary rounded-end" type="submit">
                <i class="ri ri-search-line"></i>
            </button>
        </form>
    </div>

    <!-- Right side -->
    <ul class="navbar-nav flex-row align-items-center">

        {{-- Kalau sudah login --}}
        @auth
            <li class="nav-item me-3">
                <a href="{{ url('/manage/documents') }}" class="nav-link">
                    <i class="ri ri-file-list-line me-1"></i> Dokumen
                </a>
            </li>
            @auth
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item me-3 position-relative">
                        <a href="{{ route('documents.expiring') }}" class="nav-link position-relative">
                            <i class="bi bi-bell fs-6"></i>
                            @php
                                $countExpiring = \App\Models\Document::where('jenis_dokumen', 5)
                                    ->where('status_verifikasi', 2)
                                    // Cek dokumen belum expired
                                    ->whereRaw('DATE_ADD(tanggal_penetapan, INTERVAL periode_berlaku YEAR) >= ?', [
                                        now(),
                                    ])
                                    // Opsional: cuma yang mendekati expired 6 bulan terakhir
                                    ->whereRaw('DATE_ADD(tanggal_penetapan, INTERVAL periode_berlaku YEAR) <= ?', [
                                        now()->addMonths(6),
                                    ])
                                    ->count();
                            @endphp

                            @if ($countExpiring > 0)
                                <span class="badge rounded-pill bg-danger"
                                    style="position: absolute; top: 0; right: 0; font-size: 0.65rem; padding: 0.25em 0.4em; transform: translate(0%, -0%);">
                                    {{ $countExpiring }}
                                    <span class="visually-hidden">documents expiring</span>
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
            @endauth



            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri ri-user-line me-2"></i>
                    <span>{{ Auth::user()->username ?? Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ url('pages/account-settings-account') }}">
                            <i class="ri ri-settings-4-line me-2"></i> Settings
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="ri ri-logout-box-r-line me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @endauth

        {{-- Kalau guest (opsional, bisa diaktifkan lagi kalau perlu) --}}
        @guest
            <li class="nav-item me-2">
                <a href="{{ route('auth-login-basic') }}" class="btn btn-outline-primary">Login</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('auth-register-basic') }}" class="btn btn-primary">Register</a>
            </li>
        @endguest

    </ul>
</nav>
<!-- /Navbar -->
