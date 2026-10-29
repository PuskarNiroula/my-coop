@extends('layouts.app')

@section('title', 'Savings & Withdrawals Report')

@section('page-title', 'Savings & Withdrawals Report')

@section('content')
    @php
        $activeValue = \Saving\Enum\SavingTransactionStatusEnum::ACTIVE->value;

        $activeFilters = collect(request()->only([
            'member_id',
            'fiscal_year_id',
            'year_id',
            'month_id',
            'transaction_date_nepali',
        ]))->filter(fn ($value) => filled($value))->count();


        $tables = [
            [
                'key' => 'savings',
                'label' => 'Deposits',
                'title' => 'Savings deposits',
                'subtitle' => 'Money added to member accounts',
                'type' => 'savings deposit',
                'sign' => '+',
                'tone' => 'in',
                'paginator' => $savings,
                'empty' => 'No savings deposits match these filters.',
            ],
            [
                'key' => 'withdrawals',
                'label' => 'Withdrawals',
                'title' => 'Withdrawals',
                'subtitle' => 'Money taken out of member accounts',
                'type' => 'withdrawal',
                'sign' => '-',
                'tone' => 'out',
                'paginator' => $withdrawals,
                'empty' => 'No withdrawals match these filters.',
            ],
        ];
    @endphp

    <div class="sr-page">

        <header class="sr-head">
            <div class="sr-head-text">
                <h1 class="sr-title">Savings &amp; withdrawals</h1>
                <p class="sr-lead">Review every deposit and withdrawal, filter by member or period, and cancel mistakes with a recorded reason.</p>
            </div>
        </header>

        <section class="sr-panel sr-filters">
            <form method="GET" action="{{ route('saving-reports.index') }}">
                <div class="sr-filters-head">
                    <h2 class="sr-panel-title">
                        <i class="bi bi-sliders2"></i>
                        Filters
                    </h2>
                    @if($activeFilters > 0)
                        <span class="sr-chip">{{ $activeFilters }} applied</span>
                    @endif
                </div>

                <div class="sr-filter-grid">
                    <div class="sr-field sr-field-wide">
                        <label for="member_id">Member</label>
                        <select name="member_id" id="member_id" class="sr-input">
                            <option value="">All members</option>
                            @foreach($members as $member)
                                <option value="{{ $member->member_id }}"
                                    @selected(request('member_id') == $member->member_id)>
                                    {{ $member->name }}@if(!empty($member->member_code)) ({{ $member->member_code }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sr-field">
                        <label for="fiscal_year_id">Fiscal year</label>
                        <select name="fiscal_year_id" id="fiscal_year_id" class="sr-input">
                            <option value="">All fiscal years</option>
                            @foreach($fiscalYears as $fiscalYear)
                                <option value="{{ $fiscalYear->fiscal_year_id }}"
                                    @selected(request('fiscal_year_id') == $fiscalYear->fiscal_year_id)>
                                    {{ $fiscalYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sr-field">
                        <label for="year_id">Year</label>
                        <select name="year_id" id="year_id" class="sr-input">
                            <option value="">All years</option>
                            @foreach($years as $year)
                                <option value="{{ $year->year_id }}"
                                    @selected(request('year_id') == $year->year_id)>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sr-field">
                        <label for="month_id">Month</label>
                        <select name="month_id" id="month_id" class="sr-input">
                            <option value="">All months</option>
                            @foreach($months as $month)
                                <option value="{{ $month->month_id }}"
                                    @selected(request('month_id') == $month->month_id)>
                                    {{ $month->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sr-field">
                        <label for="transaction_date_nepali">Nepali date</label>
                        <input
                            type="text"
                            data-nepali-date
                            name="transaction_date_nepali"
                            id="transaction_date_nepali"
                            class="sr-input"
                            value="{{ request('transaction_date_nepali') }}"
                            placeholder="2083-07-12"
                            autocomplete="off"
                        >
                    </div>

                    <div class="sr-filter-actions">
                        <button type="submit" class="sr-btn sr-btn-primary">
                            <i class="bi bi-funnel"></i>
                            Apply filters
                        </button>
                        <a href="{{ route('saving-reports.index') }}" class="sr-btn sr-btn-ghost">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        @if(session('success'))
            <div class="sr-alert sr-alert-success alert alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="sr-alert sr-alert-error alert alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="sr-tabs" role="tablist" aria-label="Transaction type">
            @foreach($tables as $table)
                <button
                    type="button"
                    class="sr-tab sr-tab-{{ $table['tone'] }} {{ $loop->first ? 'is-active' : '' }}"
                    role="tab"
                    id="tab-{{ $table['key'] }}"
                    aria-controls="panel-{{ $table['key'] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    data-tab="{{ $table['key'] }}"
                >
                    {{ $table['label'] }}
                    <span class="sr-tab-count">{{ number_format($table['paginator']->total()) }}</span>
                </button>
            @endforeach
        </div>

        @foreach($tables as $table)
            @php $paginator = $table['paginator']; @endphp

            <section
                class="sr-panel sr-table-panel {{ $loop->first ? '' : 'is-hidden' }}"
                id="panel-{{ $table['key'] }}"
                role="tabpanel"
                aria-labelledby="tab-{{ $table['key'] }}"
                data-panel="{{ $table['key'] }}"
            >
                <div class="sr-panel-head">
                    <div>
                        <h2 class="sr-panel-title">{{ $table['title'] }}</h2>
                        <p class="sr-panel-sub">{{ $table['subtitle'] }}</p>
                    </div>
                    <span class="sr-chip sr-chip-{{ $table['tone'] }}">
                        {{ number_format($paginator->total()) }} {{ \Illuminate\Support\Str::plural('record', $paginator->total()) }}
                    </span>
                </div>

                <div class="sr-table-wrap">
                    <table class="sr-table">
                        <thead>
                        <tr>
                            <th class="sr-col-index">#</th>
                            <th>Member</th>
                            <th class="sr-col-amount">Amount</th>
                            <th>Period</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="sr-col-action">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($paginator as $transaction)
                            @php
                                $memberName = $transaction->account?->member?->name;
                                $isActive = $transaction->status === $activeValue;
                                $initials = $memberName
                                    ? collect(preg_split('/\s+/', trim($memberName)))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                        ->implode('')
                                    : '?';
                            @endphp

                            <tr class="{{ $isActive ? '' : 'is-cancelled' }}">
                                <td class="sr-col-index" data-label="#">{{ $paginator->firstItem() + $loop->index }}</td>

                                <td data-label="Member">
                                    <div class="sr-member">
                                        <span class="sr-avatar sr-avatar-{{ $table['tone'] }}">{{ $initials }}</span>
                                        <span class="sr-member-name">{{ $memberName ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="sr-col-amount" data-label="Amount">
                                    <span class="sr-amount sr-amount-{{ $table['tone'] }}">
                                        {{ $table['sign'] }} Rs. {{ number_format((float) $transaction->amount, 2) }}
                                    </span>
                                </td>

                                <td data-label="Period">
                                    <div class="sr-period">
                                        <span>{{ $transaction->month?->name ?? '-' }} {{ $transaction->year?->name }}</span>
                                        <small>{{ $transaction->fiscalYear?->name ?? '-' }}</small>
                                    </div>
                                </td>

                                <td data-label="Date" class="sr-nowrap">{{ $transaction->transaction_date_nepali ?: '-' }}</td>

                                <td data-label="Description">
                                    <span class="sr-desc" title="{{ $transaction->description }}">{{ $transaction->description ?: '-' }}</span>
                                </td>

                                <td data-label="Status">
                                    <span class="sr-status {{ $isActive ? 'sr-status-active' : 'sr-status-cancelled' }}">
                                        {{ $isActive ? 'Active' : ucfirst((string) $transaction->status) }}
                                    </span>
                                </td>

                                <td class="sr-col-action" data-label="Action">
                                    @if($isActive)
                                        <form
                                            method="POST"
                                            action="{{ route('saving-reports.cancel', $transaction->getKey()) }}"
                                            class="cancel-transaction-form"
                                            data-type="{{ $table['type'] }}"
                                            data-amount="{{ number_format((float) $transaction->amount, 2) }}"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="cancellation_reason" class="cancellation-reason">
                                            <button type="submit" class="sr-btn sr-btn-danger sr-btn-sm">
                                                <i class="bi bi-x-circle"></i>
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="sr-muted">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="sr-empty-row">
                                <td colspan="8">
                                    <div class="sr-empty">
                                        <span class="sr-empty-icon"><i class="bi bi-inbox"></i></span>
                                        <strong>{{ $table['empty'] }}</strong>
                                        <span>Try changing the filters or reset them to see everything.</span>
                                        @if($activeFilters > 0)
                                            <a href="{{ route('saving-reports.index') }}" class="sr-btn sr-btn-ghost sr-btn-sm">Reset filters</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($paginator->hasPages())
                    <div class="sr-pagination">
                        <p class="sr-pagination-info">
                            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ number_format($paginator->total()) }}
                        </p>
                        {{ $paginator->appends(request()->query())->links() }}
                    </div>
                @endif
            </section>
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const storageKey = 'saving-reports-tab';
            const tabs = document.querySelectorAll('.sr-tab');
            const panels = document.querySelectorAll('.sr-table-panel');

            const activateTab = (key) => {
                tabs.forEach((tab) => {
                    const active = tab.dataset.tab === key;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('is-hidden', panel.dataset.panel !== key);
                });

                try {
                    localStorage.setItem(storageKey, key);
                } catch (error) {
                }
            };

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => activateTab(tab.dataset.tab));
            });

            let saved = null;

            try {
                saved = localStorage.getItem(storageKey);
            } catch (error) {
            }

            if (saved && document.querySelector(`.sr-tab[data-tab="${saved}"]`)) {
                activateTab(saved);
            }
        })();

        document.querySelectorAll('.cancel-transaction-form').forEach((form) => {
            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();

                const reasonInput = form.querySelector('.cancellation-reason');

                if (typeof Swal === 'undefined') {
                    const reason = prompt('Please enter the reason for cancellation:');

                    if (reason && reason.trim()) {
                        reasonInput.value = reason.trim();
                        form.dataset.confirmed = 'true';
                        form.submit();
                    } else if (reason !== null) {
                        alert('Cancellation reason is required.');
                    }

                    return;
                }

                Swal.fire({
                    title: 'Cancel this transaction?',
                    text: `This will cancel the ${form.dataset.type} of Rs. ${form.dataset.amount}. This cannot be undone.`,
                    input: 'textarea',
                    inputLabel: 'Reason for cancellation',
                    inputPlaceholder: 'Why is this transaction being cancelled?',
                    inputAttributes: {
                        'aria-label': 'Reason for cancellation',
                        'maxlength': 1000
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Cancel transaction',
                    cancelButtonText: 'Keep it',
                    confirmButtonColor: '#b42318',
                    cancelButtonColor: '#5b6b60',
                    reverseButtons: true,
                    focusCancel: false,
                    icon: 'warning',
                    inputValidator: (value) => {
                        if (!value || !value.trim()) {
                            return 'Please provide a reason for cancellation.';
                        }

                        if (value.trim().length > 1000) {
                            return 'The reason cannot exceed 1000 characters.';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value.trim()) {
                        reasonInput.value = result.value.trim();
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    @vite('resources/css/saving-reports.css')
@endpush
