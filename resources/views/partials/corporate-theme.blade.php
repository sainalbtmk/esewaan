<style>
    body.corp-app,
    body.corp-guest {
        margin: 0;
        background: #eef2f6;
        color: #1c2834;
        font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
    }

    body.corp-app,
    body.corp-app *,
    body.corp-guest,
    body.corp-guest * {
        box-sizing: border-box;
    }

    body.corp-app.liquid-bg,
    body.corp-guest.liquid-bg {
        background: #eef2f6;
        background-image: none;
    }

    body.corp-app .liquid-bg-mesh,
    body.corp-guest .liquid-bg-mesh {
        display: none;
    }

    body.corp-app .glass-sidebar {
        background: #0e2744;
        border-right: 0;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-sidebar img {
        border-radius: 4px;
        box-shadow: none;
    }

    body.corp-app .sidebar-nav-link,
    body.corp-app .sidebar-nav-summary,
    body.corp-app .sidebar-nav-sublink,
    body.corp-app .sidebar-appearance-btn,
    body.corp-app .sidebar-profile-card {
        border-radius: 4px;
    }

    body.corp-app .sidebar-nav-link.is-active,
    body.corp-app .sidebar-nav-summary.is-active,
    body.corp-app .sidebar-nav-sublink.is-active,
    body.corp-app .sidebar-appearance-btn.is-active {
        background: rgba(196, 163, 90, 0.16);
        border-color: transparent;
        box-shadow: inset 3px 0 0 #c4a35a;
        color: #fff;
    }

    body.corp-app .glass-header {
        background: #fff;
        border-bottom: 3px solid #c4a35a;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-card,
    body.corp-app .glass-panel,
    body.corp-app .glass-panel-muted,
    body.corp-app .glass-table,
    body.corp-app .glass-modal,
    body.corp-app .glass-filter-bar,
    body.corp-app .glass-subtle {
        background: #fff;
        border: 1px solid #d5dee8;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(14, 39, 68, 0.05);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-kpi-card:hover {
        transform: none;
        border-color: #c4a35a;
        box-shadow: 0 8px 24px rgba(14, 39, 68, 0.08);
    }

    body.corp-app .glass-kpi-card[href="#peringatan-tiga"] {
        background: #fef3f2;
        border-color: #fecdca;
    }

    body.corp-app .glass-kpi-card[href="#peringatan-tiga"]:hover {
        border-color: #9d1c2e;
    }

    body.corp-app .glass-table thead,
    body.corp-app .glass-card.glass-table thead {
        background: #f4f7fb;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-table thead th {
        color: #0e2744;
        font-weight: 600;
    }

    body.corp-app .glass-card.glass-table tbody,
    body.corp-app .glass-card.glass-table > .glass-row-hover,
    body.corp-app .glass-card > .divide-y {
        background: #fff;
    }

    body.corp-app .glass-row-neutral:hover,
    body.corp-app .glass-row-hover:hover {
        background: #f4f7fb;
    }

    body.corp-app .glass-btn-primary {
        background: #0e2744;
        border: 0;
        border-radius: 4px;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        color: #fff;
    }

    body.corp-app .glass-btn-primary:hover:not(:disabled) {
        background: #16365f;
        transform: none;
        box-shadow: none;
    }

    body.corp-app .glass-btn-secondary {
        background: #fff;
        border: 1px solid #c5d0dc;
        border-radius: 4px;
        color: #0e2744;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-input,
    body.corp-app.liquid-bg input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="image"]):not([type="range"]):not([type="color"]),
    body.corp-app.liquid-bg select,
    body.corp-app.liquid-bg textarea {
        background: #fff;
        border: 1px solid #c5d0dc;
        border-radius: 4px;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        color: #1c2834;
    }

    body.corp-app .glass-input:focus,
    body.corp-app.liquid-bg input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="image"]):not([type="range"]):not([type="color"]):focus,
    body.corp-app.liquid-bg select:focus,
    body.corp-app.liquid-bg textarea:focus {
        border-color: #0e2744;
        box-shadow: 0 0 0 3px rgba(14, 39, 68, 0.14);
        outline: none;
        background: #fff;
    }

    body.corp-app .glass-alert-success,
    body.corp-app .glass-alert-error,
    body.corp-app .glass-alert-warning,
    body.corp-guest .glass-alert-success,
    body.corp-guest .glass-alert-error,
    body.corp-guest .glass-alert-warning {
        border-radius: 4px;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    body.corp-app .glass-overlay {
        background: rgba(14, 39, 68, 0.46);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .corp-guest {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: #eef2f6;
    }

    .corp-guest .corp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        min-height: 76px;
        padding: 0.85rem 1.5rem;
        background: #0e2744;
        color: #fff;
    }

    .corp-guest .corp-lockup {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        color: #fff;
        text-decoration: none;
    }

    .corp-guest .corp-logo {
        height: 68px;
        width: auto;
        max-width: min(440px, 68vw);
        object-fit: contain;
        background: #fff;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .corp-guest .corp-system,
    .corp-guest .corp-agency {
        margin: 0;
    }

    .corp-guest .corp-system {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .corp-guest .corp-agency {
        margin-top: 0.15rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.78rem;
    }

    .corp-guest .corp-rule {
        height: 3px;
        background: #c4a35a;
    }

    .corp-guest-login {
        display: inline-flex;
        align-items: center;
        height: 36px;
        padding: 0 0.9rem;
        background: #fff;
        color: #0e2744;
        border-radius: 4px;
        font-size: 0.8125rem;
        font-weight: 700;
        text-decoration: none;
    }

    .corp-guest-main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .corp-guest-main > div {
        width: 100%;
        max-width: 440px;
    }

    .corp-guest .glass-card {
        background: #fff;
        border: 1px solid #d5dee8;
        border-radius: 6px;
        box-shadow: 0 14px 36px rgba(14, 39, 68, 0.07);
        padding: 1.75rem 1.6rem 1.4rem;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .corp-guest .glass-card h1,
    .corp-guest .glass-card h2 {
        margin: 0.35rem 0 0;
        color: #0e2744;
        font-size: 1.45rem;
        font-weight: 700;
    }

    .corp-guest form {
        display: grid;
        gap: 1rem;
    }

    .corp-guest label {
        display: block;
        margin-bottom: 0.4rem;
        color: #243140;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .corp-guest input[type="email"],
    .corp-guest input[type="password"],
    .corp-guest input[type="text"],
    .corp-guest select,
    .corp-guest textarea {
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
    }

    .corp-guest input:focus,
    .corp-guest select:focus,
    .corp-guest textarea:focus {
        outline: none;
        border-color: #0e2744;
        box-shadow: 0 0 0 3px rgba(14, 39, 68, 0.14);
    }

    .corp-guest .glass-btn-primary,
    .corp-guest button[type="submit"] {
        width: 100%;
        height: 46px;
        border: 0;
        border-radius: 4px;
        background: #0e2744;
        color: #fff;
        font: inherit;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
    }

    .corp-guest .relative {
        position: relative;
    }

    .corp-guest [data-password-toggle] {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 44px;
        border: 0;
        background: transparent;
        color: #5c6b7a;
        cursor: pointer;
    }

    .corp-guest .hidden {
        display: none !important;
    }

    .corp-guest-footer {
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

    html[data-theme="dark"] body.corp-app {
        background: #0b1a2c !important;
        background-image: none !important;
        color: #e7eef6;
    }

    html[data-theme="dark"] body.corp-app .glass-card,
    html[data-theme="dark"] body.corp-app .glass-panel,
    html[data-theme="dark"] body.corp-app .glass-panel-muted,
    html[data-theme="dark"] body.corp-app .glass-table,
    html[data-theme="dark"] body.corp-app .glass-modal,
    html[data-theme="dark"] body.corp-app .glass-filter-bar {
        background: #12263f !important;
        border: 1px solid #2c4664 !important;
        color: #e7eef6;
        box-shadow: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    html[data-theme="dark"] body.corp-app .glass-header {
        background: #0e2744 !important;
        border-bottom: 3px solid #c4a35a !important;
    }

    html[data-theme="dark"] body.corp-app .glass-header h1,
    html[data-theme="dark"] body.corp-app .glass-header p {
        color: #fff !important;
    }

    html[data-theme="dark"] body.corp-app .glass-table thead,
    html[data-theme="dark"] body.corp-app .glass-card.glass-table thead,
    html[data-theme="dark"] body.corp-app .glass-card.glass-table tbody {
        background: #0e2744 !important;
    }

    html[data-theme="dark"] body.corp-app .glass-input,
    html[data-theme="dark"] body.corp-app.liquid-bg input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="image"]):not([type="range"]):not([type="color"]),
    html[data-theme="dark"] body.corp-app.liquid-bg select,
    html[data-theme="dark"] body.corp-app.liquid-bg textarea {
        background: #0e2744 !important;
        border-color: #3d5874 !important;
        color: #f4f7fb !important;
        box-shadow: none !important;
    }
</style>
