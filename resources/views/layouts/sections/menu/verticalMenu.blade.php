@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    $configData = Helper::appClasses();
    $user = Auth::user();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
    @foreach ($configData['menuAttributes'] as $attribute => $value)
        {{ $attribute }}="{{ $value }}"
    @endforeach>

    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link gap-xl-0 gap-2">
                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                {{-- <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('variables.templateName') }}</span> --}}
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <!-- SVG icon here -->
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            @php
                $shouldHide = $user && $user->role === 'operator' && in_array($menu->name ?? '', ['User Management', 'Verifikasi Dokumen']);
            @endphp

            @if (!$shouldHide)
                {{-- Menu Header --}}
                @if (isset($menu->menuHeader))
                    <li class="menu-header small mt-5">
                        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                    </li>
                @endif

                {{-- Menu Item --}}
                @if (isset($menu->url) && isset($menu->icon) && isset($menu->name))
                    @php
                        $activeClass = null;
                        $currentRouteName = Route::currentRouteName();

                        if ($currentRouteName === $menu->slug) {
                            $activeClass = 'active';
                        } elseif (isset($menu->submenu)) {
                            if (is_array($menu->slug)) {
                                foreach ($menu->slug as $slug) {
                                    if (str_starts_with($currentRouteName, $slug)) {
                                        $activeClass = 'active open';
                                        break;
                                    }
                                }
                            } elseif (str_starts_with($currentRouteName, $menu->slug)) {
                                $activeClass = 'active open';
                            }
                        }
                    @endphp

                    <li class="menu-item {{ $activeClass }}">
                        <a href="{{ url($menu->url) }}"
                           class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                           @if (!empty($menu->target)) target="_blank" @endif>
                            <i class="{{ $menu->icon }}"></i>
                            <div>{{ __($menu->name) }}</div>
                            @isset($menu->badge)
                                <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
                            @endisset
                        </a>

                        {{-- Submenu --}}
                        @isset($menu->submenu)
                            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                        @endisset
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
</aside>