@php
    $menus = [
        [
            'label' => 'Members',
            'icon' => 'users',
            'key' => 'members',
            'items' => [
                ['label' => 'All Members', 'url' => '/members'],
                ['label' => 'Add Member', 'url' => '/members/create'],
                ['label' => 'Import Members', 'url' => '/members/import'],
            ],
        ],
        [
            'label' => 'Day Book',
            'icon' => 'receipt',
            'key' => 'day-book',
            'items' => [
                ['label' => 'Manage Day Books', 'url' => '/day-books'],
            ],
        ],
        [
            'label' => 'Savings',
            'icon' => 'wallet',
            'key' => 'savings',
            'items' => [
                ['label' => 'Savings Dashboard', 'url' => '/savings'],
                [
                    'label' => 'Saving Accounts',
                    'url' => '/savings/accounts',
                    'key' => 'saving-accounts',
                    'children' => [
                        ['label' => 'View Accounts', 'url' => '/savings/accounts'],
                        ['label' => 'Create Account', 'url' => '/savings/accounts/create'],
                        ['label' => 'Import Accounts', 'url' => '/savings/accounts/import'],
                    ],
                ],
                ['label' => 'New Deposit', 'url' => '/savings/deposit/create'],
                ['label' => 'Withdrawals', 'url' => '/savings/withdrawals/create'],
                ['label' => 'Savings Reports', 'url' => '/savings/reports'],
            ],
        ],
        [
            'label' => 'Loans',
            'icon' => 'landmark',
            'key' => 'loans',
            'items' => [
                ['label' => 'All Loans', 'url' => '/loans'],
                ['label' => 'New Loan Application', 'url' => '/loans/create'],
                ['label' => 'Loan Repayments', 'url' => '/loan-repayments'],
                ['label' => 'Loan Types', 'url' => '/loan-types'],
            ],
        ],
        [
            'label' => 'Shares',
            'icon' => 'chart',
            'key' => 'shares',
            'items' => [
                ['label' => 'Share Overview', 'url' => '/shares'],
                ['label' => 'Issue Shares', 'url' => '/shares/create'],
                ['label' => 'Share Transactions', 'url' => '/share-transactions'],
                ['label' => 'Share Reports', 'url' => '/shares/reports'],
            ],
        ],
        [
            'label' => 'Inventory Management',
            'icon' => 'package',
            'key' => 'inventory',
            'items' => [
                ['label' => 'Inventory Overview', 'url' => '/inventory'],
                ['label' => 'Products', 'url' => '/inventory/products'],
                ['label' => 'Categories', 'url' => '/inventory/categories'],
                ['label' => 'Stock Movements', 'url' => '/inventory/stock-movements'],
                ['label' => 'Suppliers', 'url' => '/inventory/suppliers'],
            ],
        ],
        [
            'label' => 'Expense Tracking',
            'icon' => 'receipt',
            'key' => 'expenses',
            'items' => [
                ['label' => 'All Expenses', 'url' => '/expenses'],
                ['label' => 'Add Expense', 'url' => '/expenses/create'],
                ['label' => 'Expense Categories', 'url' => '/expense-categories'],
                ['label' => 'Expense Reports', 'url' => '/expenses/reports'],
            ],
        ],
    ];

    $setupItems = [
        [
            'label' => 'Account Setup',
            'url' => '/account-setup',
        ],
        [
            'label' => 'Fiscal Year Setup',
            'url' => '/fiscal-year-setup',
        ],
        [
            'label' => 'Year Setup',
            'url' => '/year-setup',
        ],
    ];

    $currentPath = '/' . trim(request()->path(), '/');

    $normalizePath = fn ($url) => rtrim($url, '/') ?: '/';

    $isItemActive = function ($url) use ($currentPath, $normalizePath) {
        $normalizedUrl = $normalizePath($url);

        return $currentPath === $normalizedUrl;
    };

    $isPathActive = function ($url) use ($currentPath, $normalizePath) {
        $normalizedUrl = $normalizePath($url);

        return $currentPath === $normalizedUrl
            || str_starts_with($currentPath, $normalizedUrl . '/');
    };

    $isMenuExpanded = function ($menu) use ($isPathActive) {
        foreach ($menu['items'] as $item) {
            if ($isPathActive($item['url'])) {
                return true;
            }

            foreach ($item['children'] ?? [] as $child) {
                if ($isPathActive($child['url'])) {
                    return true;
                }
            }
        }

        return false;
    };

    $setupActive = collect($setupItems)->contains(
        fn ($item) => $isPathActive($item['url'])
    );

    $iconPaths = [
        'dashboard' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'wallet' => '<rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 9h18M16 14h2"/><path d="M5 5V4a1 1 0 0 1 1-1h12"/>',
        'landmark' => '<path d="m3 10 9-7 9 7M4 10h16M5 10v9M10 10v9M14 10v9M19 10v9M3 21h18M2 19h20"/>',
        'chart' => '<path d="M3 3v18h18M8 15l4-5 4 3 5-7"/>',
        'package' => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="M3 8v9l9 5 9-5V8M12 13v9"/>',
        'receipt' => '<path d="M4 3h16v18l-4-2-4 2-4-2-4 2V3Z"/><path d="M8 8h8M8 12h8M8 16h4"/>',
        'chevron' => '<path d="m9 18 6-6-6-6"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 2Z"/>',
    ];

    $dashboardActive = $isItemActive('/dashboard');
@endphp

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
    <a href="{{ url('/dashboard') }}" class="brand">
        <span class="brand-mark">
            <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.4"/>
                <path d="M24 35V20M24 27C15 27 12 21 12 16C21 16 24 21 24 27ZM24 23C24 15 29 11 36 11C36 19 32 23 24 23Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 36H33" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </span>

        <span class="brand-name">
            <strong>JAY DURGA</strong>
            <small>KRISHI SAHAKARI SANSTHA</small>
        </span>
    </a>

    <div class="sidebar-divider"></div>

    <div class="nav-section-label">WORKSPACE</div>

    <nav class="sidebar-nav" aria-label="Main navigation">
        <a href="{{ url('/dashboard') }}" class="nav-link {{ $dashboardActive ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                {!! $iconPaths['dashboard'] !!}
            </svg>
            <span>Dashboard</span>
        </a>

        @foreach ($menus as $menu)
            @php
                $expanded = $isMenuExpanded($menu);
            @endphp

            <div class="nav-group {{ $expanded ? 'is-open' : '' }}">
                <button
                    type="button"
                    class="nav-link nav-toggle {{ $expanded ? 'active' : '' }}"
                    aria-expanded="{{ $expanded ? 'true' : 'false' }}"
                    aria-controls="submenu-{{ $menu['key'] }}"
                    data-menu-toggle
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        {!! $iconPaths[$menu['icon']] !!}
                    </svg>

                    <span>{{ $menu['label'] }}</span>

                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        {!! $iconPaths['chevron'] !!}
                    </svg>
                </button>

                <div class="submenu" id="submenu-{{ $menu['key'] }}">
                    <div class="submenu-inner">
                        @foreach ($menu['items'] as $item)
                            @php
                                $hasChildren = !empty($item['children']);

                                $childActive = $hasChildren && collect($item['children'])->contains(
                                    fn ($child) => $isPathActive($child['url'])
                                );

                                $itemExpanded = $childActive;

                                $itemActive = $isPathActive($item['url']) || $childActive;
                            @endphp

                            @if ($hasChildren)
                                <div class="submenu-group {{ $itemExpanded ? 'is-open' : '' }}">
                                    <button
                                        type="button"
                                        class="submenu-link submenu-toggle {{ $itemActive ? 'active' : '' }}"
                                        data-submenu-toggle
                                        aria-expanded="{{ $itemExpanded ? 'true' : 'false' }}"
                                        aria-controls="nested-{{ $item['key'] }}"
                                    >
                                        <span class="submenu-dot"></span>
                                        <span>{{ $item['label'] }}</span>

                                        <svg class="nested-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $iconPaths['chevron'] !!}
                                        </svg>
                                    </button>

                                    <div class="nested-submenu" id="nested-{{ $item['key'] }}">
                                        @foreach ($item['children'] as $child)
                                            <a
                                                href="{{ url($child['url']) }}"
                                                class="nested-submenu-link {{ $isPathActive($child['url']) ? 'active' : '' }}"
                                            >
                                                <span class="submenu-dot"></span>
                                                <span>{{ $child['label'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a
                                    href="{{ url($item['url']) }}"
                                    class="submenu-link {{ $isPathActive($item['url']) ? 'active' : '' }}"
                                >
                                    <span class="submenu-dot"></span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </nav>

    <div class="nav-group {{ $setupActive ? 'is-open' : '' }}">
        <button
            type="button"
            class="nav-link nav-toggle {{ $setupActive ? 'active' : '' }}"
            aria-expanded="{{ $setupActive ? 'true' : 'false' }}"
            aria-controls="submenu-setup"
            data-menu-toggle
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                {!! $iconPaths['settings'] !!}
            </svg>

            <span>Setup</span>

            <svg
                class="nav-chevron"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                {!! $iconPaths['chevron'] !!}
            </svg>
        </button>

        <div class="submenu" id="submenu-setup">
            <div class="submenu-inner">
                @foreach ($setupItems as $item)
                    <a
                        href="{{ url($item['url']) }}"
                        class="submenu-link {{ $isPathActive($item['url']) ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="sidebar-bottom">
        <div class="sidebar-divider"></div>

        <a href="{{ url('/settings') }}" class="nav-link {{ $isPathActive('/settings') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                {!! $iconPaths['settings'] !!}
            </svg>
            <span>Settings</span>
        </a>

        <div class="user-profile">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>

            <div class="user-details">
                <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                <span>Administrator</span>
            </div>

            <button
                type="button"
                class="profile-menu-toggle"
                id="profileMenuToggle"
                aria-label="Open profile menu"
                aria-expanded="false"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="5" cy="12" r="1"/>
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="19" cy="12" r="1"/>
                </svg>
            </button>

            <div class="profile-menu" id="profileMenu">
                <a href="{{ url('/profile') }}">My Profile</a>

                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</aside>
