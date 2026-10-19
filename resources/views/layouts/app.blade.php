<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123b2a">
    <title>@yield('title', 'Dashboard') | Jay Durga Krishi Sahakari Sanstha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        rel="stylesheet"
        href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css"
    >

    @stack('styles')
    @livewireStyles
</head>
<body>
<div class="app-shell">
    @include('layouts.sidebar')


    <div class="main-panel">
        <header class="topbar">
            <div class="topbar-left">
                <button class="icon-button mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Open navigation">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="breadcrumb">
                    <span>Workspace</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                    <strong>@yield('page-title', 'Dashboard')</strong>
                </div>
            </div>

            <div class="topbar-right">
                <div class="topbar-date">{{ now()->format('d M Y') }}</div>

                <button class="icon-button notification-button" type="button" aria-label="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                    </svg>
                    <span class="notification-dot"></span>
                </button>

                <div class="topbar-divider"></div>

                <div class="topbar-user">
                    <div class="avatar avatar-small">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <div>
                        <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                        <span>Admin Portal</span>
                    </div>
                </div>
            </div>
        </header>

            <main class="page-content">
                @if (session('success'))
                    <div class="flash-message flash-success" role="alert">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss success message">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flash-message flash-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss error message">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-message flash-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            <strong>Please correct the following errors:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss validation errors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>


        <footer class="app-footer">
            <span>© {{ date('Y') }} Jay Durga Krishi Sahakari Sanstha</span>
            <span>Rooted in trust. Growing with you.</span>
        </footer>
    </div>
</div>
    @livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-nepali-date]').forEach(function (input) {
            if (input.dataset.nepaliDateInitialized === 'true') return;

            input.dataset.nepaliDateInitialized = 'true';

            const target = input.dataset.target
                ? document.querySelector(input.dataset.target)
                : null;

            input.nepaliDatePicker({
                dateFormat: 'YYYY-MM-DD',
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 100,
                readOnlyInput: true,
                onChange: function () {
                    if (target) {
                        target.value = input.value;
                        target.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        });
    });
</script>


@stack('scripts')
</body>
</html>
