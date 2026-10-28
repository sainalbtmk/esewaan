<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        @if(auth()->user()->isAdmin())
            <meta name="sidebar-notifications-url" content="{{ route('sidebar-notifications') }}">
        @endif
    @endauth
    <title>{{ config('app.name') }} - @yield('title', 'SPEKTRA')</title>
    <link rel="icon" href="{{ asset('favicon.png') . '?v=' . filemtime(public_path('favicon.png')) }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') . '?v=' . filemtime(public_path('favicon.png')) }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    @include('partials.appearance-boot')
    @include('partials.sidebar-scroll-styles')
    @include('partials.glass-theme')
    @include('partials.appearance-theme')
    @include('partials.corporate-theme')
    @stack('styles')
</head>
<body @class([
    'min-h-screen font-sans antialiased',
    'liquid-bg' => auth()->check(),
    'corp-app' => auth()->check(),
    'corp-auth' => request()->routeIs('login'),
    'corp-guest' => ! auth()->check() && ! request()->routeIs('login'),
])>
    @include('partials.global-loading-overlay')
    <div class="liquid-bg-mesh" aria-hidden="true">
        <div class="liquid-orb liquid-orb-1"></div>
        <div class="liquid-orb liquid-orb-2"></div>
        <div class="liquid-orb liquid-orb-3"></div>
    </div>
    @auth
    <div class="flex h-screen overflow-hidden">
        {{-- Desktop Sidebar --}}
        <aside id="sidebar" class="glass-sidebar hidden lg:flex h-full min-h-0 w-60 flex-shrink-0 flex-col overflow-hidden transition-all duration-300">
            <div class="flex h-full min-h-0 flex-col">
                <div class="flex flex-shrink-0 items-center border-b border-white/10 px-3 py-3">
                    @php
                        $logoPath = null;
                        $custom = config('app.logo');
                        if ($custom && file_exists(public_path($custom))) {
                            $logoPath = $custom;
                        } else {
                            foreach (['images/spektra-logo.jpg', 'aadk.png', 'logo.png', 'logo.jpg', 'logo.jpeg', 'logo.webp', 'images/logo.png', 'images/logo.jpg', 'images/logo.jpeg', 'images/logo.webp'] as $name) {
                                if (file_exists(public_path($name))) {
                                    $logoPath = $name;
                                    break;
                                }
                            }
                        }
                    @endphp
                    @if($logoPath)
                        <img src="{{ asset($logoPath) }}" alt="SPEKTRA" class="h-auto w-full rounded-lg bg-white object-contain p-1.5">
                    @else
                        <div>
                            <p class="text-sm font-semibold leading-tight text-white">SPEKTRA</p>
                            <p class="text-xs leading-snug text-white/50">Sistem Pemantauan Kontrak Sewaan Premis AADK</p>
                        </div>
                    @endif
                </div>
                <nav class="sidebar-scroll min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-y-contain px-3 py-4 pr-2 scroll-smooth">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Papan Pemuka</span>
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    @include('partials.sidebar-pengurusan-permohonan')
                    @include('partials.sidebar-kontrak-sewaan')
                    @include('partials.sidebar-jana-laporan')
                    @include('partials.sidebar-pengurusan-pengguna')
                    @else
                    @include('partials.sidebar-kontrak-sewaan')
                    @endif
                </nav>
                @include('partials.sidebar-profile-footer')
            </div>
        </aside>

        {{-- Mobile sidebar overlay --}}
        <div id="mobile-sidebar-overlay" class="lg:hidden fixed inset-0 z-40 hidden">
            <div class="fixed inset-0 bg-black/50" id="mobile-overlay-backdrop"></div>
            <aside class="glass-sidebar relative z-50 flex h-full min-h-0 w-64 flex-col overflow-hidden shadow-xl">
                <div class="flex flex-shrink-0 items-center justify-between gap-3 border-b border-white/10 px-3 py-3">
                    <div class="min-w-0 flex-1">
                        @if($logoPath)
                            <img src="{{ asset($logoPath) }}" alt="SPEKTRA" class="h-auto w-full rounded-lg bg-white object-contain p-1.5">
                        @else
                            <p class="text-sm font-semibold text-white">SPEKTRA</p>
                            <p class="text-xs text-white/50">Sistem Pemantauan Kontrak Sewaan Premis AADK</p>
                        @endif
                    </div>
                    <button type="button" id="mobile-close-sidebar" class="p-2 text-white/60 hover:text-white">&times;</button>
                </div>
                <nav class="sidebar-scroll min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-y-contain px-3 py-4 pr-2 scroll-smooth">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Papan Pemuka</span>
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    @include('partials.sidebar-pengurusan-permohonan')
                    @include('partials.sidebar-kontrak-sewaan')
                    @include('partials.sidebar-jana-laporan')
                    @include('partials.sidebar-pengurusan-pengguna')
                    @else
                    @include('partials.sidebar-kontrak-sewaan')
                    @endif
                </nav>
                @include('partials.sidebar-profile-footer')
            </aside>
        </div>

        @include('partials.sidebar-state')

        {{-- Main content --}}
        <div class="flex min-h-0 flex-1 flex-col min-w-0">
            <header class="glass-header relative z-30 flex h-20 flex-shrink-0 items-center justify-between px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" id="mobile-open-sidebar" class="lg:hidden p-2 rounded-xl glass-subtle hover:bg-white/60 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h1 class="text-base font-semibold text-slate-900">@yield('header_title', 'SPEKTRA')</h1>
                        <p class="text-xs text-slate-500 hidden sm:block">@yield('header_subtitle', 'Sistem Pemantauan Kontrak Sewaan Premis AADK')</p>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                    @include('partials.header-inbox')
                @endif
            </header>

            <main class="min-h-0 flex-1 overflow-y-auto">
                <div class="p-4 lg:p-6">
                    @if(session('success'))
                    <div class="mb-4 rounded-xl glass-alert-success p-4 text-green-800 text-sm" role="alert">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="mb-4 rounded-xl glass-alert-error p-4 text-red-800 text-sm" role="alert">{{ session('error') }}</div>
                    @endif
                    @if(isset($errors) && $errors->any())
                    <div class="mb-4 rounded-xl glass-alert-warning p-4 text-amber-900 text-sm">
                        <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script>
        document.getElementById('mobile-open-sidebar')?.addEventListener('click', function() {
            document.getElementById('mobile-sidebar-overlay').classList.remove('hidden');
        });
        function closeMobileSidebar() {
            document.getElementById('mobile-sidebar-overlay').classList.add('hidden');
        }
        document.getElementById('mobile-close-sidebar')?.addEventListener('click', closeMobileSidebar);
        document.getElementById('mobile-overlay-backdrop')?.addEventListener('click', closeMobileSidebar);
    </script>
    @include('partials.sidebar-notifications-poll')
    @include('partials.list-interaction')
    @else
    @if(request()->routeIs('login'))
    <main>
        @yield('content')
    </main>
    @else
    @php
        $guestLogoPath = null;
        $custom = config('app.logo');
        if ($custom && file_exists(public_path($custom))) {
            $guestLogoPath = $custom;
        } else {
            foreach (['images/spektra-logo.jpg', 'aadk.png', 'logo.png', 'logo.jpg', 'logo.jpeg', 'logo.webp', 'images/logo.png', 'images/logo.jpg', 'images/logo.jpeg', 'images/logo.webp'] as $name) {
                if (file_exists(public_path($name))) {
                    $guestLogoPath = $name;
                    break;
                }
            }
        }
    @endphp
    <div class="corp-guest">
        <header class="corp-header">
            <a href="{{ route('login') }}" class="corp-lockup">
                @if($guestLogoPath)
                    <img src="{{ asset($guestLogoPath) }}" alt="SPEKTRA" class="corp-logo">
                @else
                    <div>
                        <p class="corp-system">SPEKTRA</p>
                        <p class="corp-agency">Agensi Antidadah Kebangsaan</p>
                    </div>
                @endif
            </a>
            <a href="{{ route('login') }}" class="corp-guest-login">Log Masuk</a>
        </header>
        <div class="corp-rule" aria-hidden="true"></div>
        <main class="corp-guest-main">
            <div>
                @if(session('success'))<div class="mb-4 glass-alert-success p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="mb-4 glass-alert-error p-4 text-sm text-red-800">{{ session('error') }}</div>@endif
                @if(isset($errors) && $errors->any())<div class="mb-4 glass-alert-warning p-4 text-sm text-amber-900"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                @yield('content')
            </div>
        </main>
        <footer class="corp-guest-footer">
            <span>© {{ date('Y') }} Agensi Antidadah Kebangsaan. Hak cipta terpelihara.</span>
            <span>Sistem rasmi kegunaan dalaman</span>
        </footer>
    </div>
    @endif
    @endauth

    @stack('scripts')
    <script>
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }
            var message = form.getAttribute('data-confirm');
            if (message && ! window.confirm(message)) {
                event.preventDefault();
            }
        }, true);

        document.addEventListener('click', function (event) {
            var trigger = event.target instanceof Element ? event.target.closest('[data-confirm-click]') : null;
            if (! trigger) {
                return;
            }
            var message = trigger.getAttribute('data-confirm-click');
            if (message && ! window.confirm(message)) {
                event.preventDefault();
            }
        }, true);
    </script>
</body>
</html>
