@extends('layouts.app')

@section('title', 'Log Masuk')

@push('styles')
<style>
    body.corp-auth {
        margin: 0;
        min-height: 100vh;
        background: #eef2f6;
        color: #1c2834;
        font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    body.corp-auth .liquid-bg-mesh {
        display: none;
    }

    body.corp-auth,
    body.corp-auth *,
    body.corp-auth *::before,
    body.corp-auth *::after {
        box-sizing: border-box;
    }

    .corp-login {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: #eef2f6;
    }

    .corp-login h1,
    .corp-login h2,
    .corp-login p,
    .corp-login ul {
        margin: 0;
    }

    .corp-login a {
        color: inherit;
    }

    .corp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        min-height: 76px;
        padding: 0.85rem 1.5rem;
        background: #0e2744;
        color: #fff;
    }

    .corp-lockup {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 0;
    }

    .corp-logo {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        object-fit: contain;
        background: #fff;
        border-radius: 4px;
        padding: 3px;
    }

    .corp-logo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        background: #fff;
        color: #0e2744;
        border-radius: 4px;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .corp-system {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        line-height: 1.2;
    }

    .corp-agency {
        margin-top: 0.15rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.78rem;
        line-height: 1.35;
    }

    .corp-header-meta {
        margin: 0;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-align: right;
    }

    .corp-rule {
        height: 3px;
        background: #c4a35a;
    }

    .corp-main {
        flex: 1;
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
    }

    .corp-aside {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 3.5rem 3.25rem;
        background-color: #123056;
        background-image:
            linear-gradient(180deg, rgba(255, 255, 255, 0.045), transparent 42%),
            repeating-linear-gradient(-45deg, transparent, transparent 14px, rgba(255, 255, 255, 0.018) 14px, rgba(255, 255, 255, 0.018) 15px);
        color: #fff;
    }

    .corp-kicker {
        color: #c4a35a;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .corp-aside h1 {
        max-width: 14ch;
        margin-top: 0.85rem;
        font-size: 2.35rem;
        font-weight: 600;
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .corp-aside-copy {
        max-width: 36rem;
        margin-top: 1rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.98rem;
        line-height: 1.6;
    }

    .corp-points {
        display: grid;
        gap: 0.7rem;
        margin-top: 1.75rem;
        padding: 0;
        list-style: none;
    }

    .corp-points li {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.92rem;
    }

    .corp-points li::before {
        content: "";
        width: 0.45rem;
        height: 0.45rem;
        flex-shrink: 0;
        background: #c4a35a;
    }

    .corp-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1.5rem 2rem;
    }

    .corp-card {
        width: 100%;
        max-width: 420px;
        padding: 1.85rem 1.75rem 1.5rem;
        background: #fff;
        border: 1px solid #d5dee8;
        border-radius: 6px;
        box-shadow: 0 14px 36px rgba(14, 39, 68, 0.07);
    }

    .corp-card h2 {
        font-size: 1.45rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #0e2744;
    }

    .corp-card-copy {
        margin-top: 0.4rem;
        margin-bottom: 1.35rem;
        color: #5c6b7a;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .corp-alert {
        margin-bottom: 1rem;
        padding: 0.75rem 0.85rem;
        border-radius: 4px;
        font-size: 0.85rem;
        line-height: 1.45;
    }

    .corp-alert ul {
        margin: 0;
        padding-left: 1.1rem;
    }

    .corp-alert-success {
        background: #ecfdf3;
        border: 1px solid #abefc6;
        color: #085d3a;
    }

    .corp-alert-error {
        background: #fef3f2;
        border: 1px solid #fecdca;
        color: #912018;
    }

    .corp-alert-warning {
        background: #fffaeb;
        border: 1px solid #fedf89;
        color: #7a4b08;
    }

    .corp-login form {
        display: grid;
        gap: 1rem;
    }

    .corp-login label {
        display: block;
        margin-bottom: 0.4rem;
        color: #243140;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .corp-login input[type="email"],
    .corp-login input[type="password"],
    .corp-login input[type="text"] {
        display: block;
        width: 100%;
        height: 44px;
        padding: 0 0.85rem;
        background: #fff;
        border: 1px solid #c5d0dc;
        border-radius: 4px;
        box-shadow: none;
        color: #1c2834;
        font: inherit;
        font-size: 0.9375rem;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .corp-login input[type="password"],
    .corp-login input[type="text"].auth-password-input {
        padding-right: 2.75rem;
    }

    .corp-login input::placeholder {
        color: #8b98a6;
    }

    .corp-login input[type="email"]:focus,
    .corp-login input[type="password"]:focus,
    .corp-login input[type="text"]:focus {
        outline: none;
        border-color: #0e2744;
        box-shadow: 0 0 0 3px rgba(14, 39, 68, 0.14);
        background: #fff;
    }

    .corp-login .relative {
        position: relative;
    }

    .corp-login [data-password-toggle] {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 44px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #5c6b7a;
        cursor: pointer;
    }

    .corp-login [data-password-toggle]:hover {
        color: #0e2744;
    }

    .corp-login [data-password-toggle] svg {
        width: 1.15rem;
        height: 1.15rem;
    }

    .corp-login .hidden {
        display: none !important;
    }

    .corp-forgot {
        display: flex;
        justify-content: flex-end;
        margin-top: -0.15rem;
    }

    .corp-forgot a {
        color: #0e2744;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
    }

    .corp-forgot a:hover {
        text-decoration: underline;
    }

    .corp-submit {
        height: 46px;
        margin-top: 0.15rem;
        border: 0;
        border-radius: 4px;
        background: #0e2744;
        color: #fff;
        font: inherit;
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
    }

    .corp-submit:hover {
        background: #16365f;
    }

    .corp-submit:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(196, 163, 90, 0.55);
    }

    .corp-note {
        margin-top: 1rem;
        color: #6b7785;
        font-size: 0.75rem;
        line-height: 1.45;
        text-align: center;
    }

    .corp-footer {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem 1.5rem;
        flex-wrap: wrap;
        padding: 0.85rem 1.5rem;
        background: #fff;
        border-top: 1px solid #d5dee8;
        color: #5c6b7a;
        font-size: 0.75rem;
    }

    @media (max-width: 900px) {
        .corp-header {
            align-items: flex-start;
        }

        .corp-header-meta {
            display: none;
        }

        .corp-main {
            grid-template-columns: 1fr;
        }

        .corp-aside {
            padding: 1.5rem 1.25rem 1.35rem;
        }

        .corp-aside h1 {
            max-width: none;
            font-size: 1.55rem;
        }

        .corp-aside-copy {
            font-size: 0.9rem;
        }

        .corp-points {
            margin-top: 1rem;
        }

        .corp-panel {
            padding: 1.25rem 1rem 1.5rem;
        }

        .corp-card {
            padding: 1.35rem 1.15rem 1.15rem;
        }

        .corp-footer {
            flex-direction: column;
        }
    }

    html[data-theme="dark"] body.corp-auth {
        background: #eef2f6;
        color: #1c2834;
    }

    html[data-theme="dark"] .corp-login input,
    html[data-theme="dark"] .corp-login .glass-input {
        background: #fff !important;
        border-color: #c5d0dc !important;
        color: #1c2834 !important;
        box-shadow: none !important;
    }

    html[data-theme="dark"] .corp-login label,
    html[data-theme="dark"] .corp-login .text-slate-700,
    html[data-theme="dark"] .corp-card h2,
    html[data-theme="dark"] .corp-card-copy,
    html[data-theme="dark"] .corp-note,
    html[data-theme="dark"] .corp-forgot a {
        color: #243140 !important;
    }

    html[data-theme="dark"] .corp-card-copy,
    html[data-theme="dark"] .corp-note {
        color: #5c6b7a !important;
    }
</style>
@endpush

@section('content')
@php
    $loginLogoPath = null;
    $custom = config('app.logo');
    if ($custom && file_exists(public_path($custom))) {
        $loginLogoPath = $custom;
    } else {
        foreach (['aadk.png', 'logo.png', 'logo.jpg', 'logo.jpeg', 'logo.webp', 'images/logo.png', 'images/logo.jpg', 'images/logo.jpeg', 'images/logo.webp'] as $name) {
            if (file_exists(public_path($name))) {
                $loginLogoPath = $name;
                break;
            }
        }
    }
@endphp

<div class="corp-login">
    <header class="corp-header">
        <div class="corp-lockup">
            @if($loginLogoPath)
                <img src="{{ asset($loginLogoPath) }}" alt="AADK" class="corp-logo">
            @else
                <div class="corp-logo-fallback" aria-hidden="true">E</div>
            @endif
            <div>
                <p class="corp-system">E-SEWAAN</p>
                <p class="corp-agency">Agensi Antidadah Kebangsaan</p>
            </div>
        </div>
        <p class="corp-header-meta">Sistem Pengurusan Kontrak Sewaan Premis</p>
    </header>
    <div class="corp-rule" aria-hidden="true"></div>

    <div class="corp-main">
        <aside class="corp-aside">
            <p class="corp-kicker">Sistem Dalaman</p>
            <h1>Kontrak Sewaan Premis</h1>
            <p class="corp-aside-copy">
                Platform rasmi bagi permohonan, pemantauan dan pelaporan kontrak sewaan premis Agensi Antidadah Kebangsaan.
            </p>
            <ul class="corp-points">
                <li>Permohonan dan semakan sewaan</li>
                <li>Pemantauan status kontrak</li>
                <li>Laporan premis mengikut negeri</li>
            </ul>
        </aside>

        <section class="corp-panel">
            <div class="corp-card">
                <h2>Log Masuk</h2>
                <p class="corp-card-copy">Sila masukkan emel dan kata laluan yang berdaftar.</p>

                @if(session('success'))
                    <div class="corp-alert corp-alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="corp-alert corp-alert-error" role="alert">{{ session('error') }}</div>
                @endif
                @if(isset($errors) && $errors->any())
                    <div class="corp-alert corp-alert-warning" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" data-no-global-loader>
                    @csrf
                    <div>
                        <label for="email">Emel</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="nama@contoh.com"
                            autocomplete="email"
                        >
                    </div>

                    <x-auth.password-field
                        label="Kata laluan"
                        name="password"
                        input-id="login-password"
                        autocomplete="current-password"
                        required
                    />

                    <div class="corp-forgot">
                        <a href="{{ route('password.request') }}">Lupa Katalaluan?</a>
                    </div>

                    <button type="submit" class="corp-submit">Log Masuk</button>
                </form>

                <p class="corp-note">Akses terhad kepada pengguna berdaftar sistem E-Sewaan.</p>
            </div>
        </section>
    </div>

    <footer class="corp-footer">
        <span>© {{ date('Y') }} Agensi Antidadah Kebangsaan. Hak cipta terpelihara.</span>
        <span>Sistem rasmi kegunaan dalaman</span>
    </footer>
</div>

@include('partials.auth-password-toggle-script')
@endsection
