
@extends('layouts.app')

@section('title', 'Savings & Withdrawals Report')

@section('page-title', 'Savings & Withdrawals Report')

@section('content')
    <div class="container-fluid py-4 savings-report-page">

        <div class="card report-card mb-4">
            <div class="report-card-header">
                <div>
                    <h5 class="mb-0">Filter Transactions</h5>
                    <small class="report-subtitle">Find transactions using the filters below</small>
                </div>
            </div>

            <div class="report-card-body">
                <form method="GET" action="{{ route('saving-reports.index') }}">
                    <div class="report-filter-grid">

                        <div class="report-field">
                            <label for="member_id">Member</label>
                            <select name="member_id" id="member_id" class="form-select">
                                <option value="">All Members</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->member_id }}"
                                        @selected(request('member_id') == $member->member_id)>
                                        {{ $member->name }}
                                        @if(!empty($member->member_code))
                                            ({{ $member->member_code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="fiscal_year_id">Fiscal Year</label>
                            <select name="fiscal_year_id" id="fiscal_year_id" class="form-select">
                                <option value="">All Fiscal Years</option>
                                @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->fiscal_year_id }}"
                                        @selected(request('fiscal_year_id') == $fiscalYear->fiscal_year_id)>
                                        {{ $fiscalYear->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="year_id">Year</label>
                            <select name="year_id" id="year_id" class="form-select">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->year_id }}"
                                        @selected(request('year_id') == $year->year_id)>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="month_id">Month</label>
                            <select name="month_id" id="month_id" class="form-select">
                                <option value="">All Months</option>
                                @foreach($months as $month)
                                    <option value="{{ $month->month_id }}"
                                        @selected(request('month_id') == $month->month_id)>
                                        {{ $month->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="transaction_date_nepali">Nepali Date</label>
                            <input
                                type="text"
                                data-nepali-date
                                name="transaction_date_nepali"
                                id="transaction_date_nepali"
                                class="form-control"
                                value="{{ request('transaction_date_nepali') }}"
                                placeholder="2083-07-12"
                            >
                        </div>

                        <div class="report-filter-actions">
                            <button type="submit" class="report-btn report-btn-primary">
                                <i class="bi bi-funnel"></i>
                                Filter
                            </button>

                            <a href="{{ route('saving-reports.index') }}"
                               class="report-btn report-btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card report-card mb-4">
            <div class="report-card-header">
                <div>
                    <h5 class="mb-0">Savings Deposits</h5>
                    <small class="report-subtitle">Deposit transaction records</small>
                </div>
                <span class="report-count">
                    {{ $savings->total() }} records
                </span>
            </div>

            <div class="report-card-body">
                <div class="report-table-wrap">
                    <table class="report-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Fiscal Year</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Transaction Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($savings as $transaction)
                            <tr>
                                <td>{{ $savings->firstItem() + $loop->index }}</td>
                                <td>{{ $transaction->account?->member?->name ?? 'N/A' }}</td>
                                <td class="report-amount">
                                    {{ number_format((float) $transaction->amount, 2) }}
                                </td>
                                <td>{{ $transaction->fiscalYear->name }}</td>
                                <td>{{ $transaction->year->name }}</td>
                                <td>{{ $transaction->month->name }}</td>
                                <td>{{ $transaction->transaction_date_nepali }}</td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    @if($transaction->status === \Saving\Enum\SavingTransactionStatusEnum::ACTIVE->value)
                                        <span class="report-status report-status-active">Active</span>
                                    @else
                                        <span class="report-status report-status-cancelled">
                                                {{ $transaction->status }}
                                            </span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->status === \Saving\Enum\SavingTransactionStatusEnum::ACTIVE->value)
                                        <form
                                            method="POST"
                                            action="{{ route('saving-reports.cancel', $transaction->getKey()) }}"
                                            class="cancel-transaction-form"
                                            data-type="savings deposit"
                                            data-amount="{{ number_format((float) $transaction->amount, 2) }}"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="cancellation_reason" class="cancellation-reason">
                                            <button type="submit" class="report-btn report-btn-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="report-empty">
                                    No savings deposits found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="report-pagination">
                    {{ $savings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <div class="card report-card">
            <div class="report-card-header">
                <div>
                    <h5 class="mb-0">Withdrawals</h5>
                    <small class="report-subtitle">Withdrawal transaction records</small>
                </div>
                <span class="report-count report-count-withdrawal">
                    {{ $withdrawals->total() }} records
                </span>
            </div>

            <div class="report-card-body">
                <div class="report-table-wrap">
                    <table class="report-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Fiscal Year</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Transaction Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($withdrawals as $transaction)
                            <tr>
                                <td>{{ $withdrawals->firstItem() + $loop->index }}</td>
                                <td>{{ $transaction->account?->member?->name ?? 'N/A' }}</td>
                                <td class="report-amount">
                                    {{ number_format((float) $transaction->amount, 2) }}
                                </td>
                                <td>{{ $transaction->fiscalYear->name }}</td>
                                <td>{{ $transaction->year->name }}</td>
                                <td>{{ $transaction->month->name }}</td>
                                <td>{{ $transaction->transaction_date_nepali }}</td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    @if($transaction->status === \Saving\Enum\SavingTransactionStatusEnum::ACTIVE->value)
                                        <span class="report-status report-status-active">Active</span>
                                    @else
                                        <span class="report-status report-status-cancelled">
                                                {{ $transaction->status }}
                                            </span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->status === \Saving\Enum\SavingTransactionStatusEnum::ACTIVE->value)
                                        <form
                                            method="POST"
                                            action="{{ route('saving-reports.cancel', $transaction->getKey()) }}"
                                            class="cancel-transaction-form"
                                            data-type="withdrawal"
                                            data-amount="{{ number_format((float) $transaction->amount, 2) }}"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="cancellation_reason" class="cancellation-reason">
                                            <button type="submit" class="report-btn report-btn-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="report-empty">
                                    No withdrawals found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="report-pagination">
                    {{ $withdrawals->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
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
                    title: 'Cancel transaction?',
                    text: `Cancel this ${form.dataset.type} of Rs. ${form.dataset.amount}?`,
                    input: 'textarea',
                    inputLabel: 'Reason for cancellation',
                    inputPlaceholder: 'Enter the reason for cancelling this transaction...',
                    inputAttributes: {
                        'aria-label': 'Reason for cancellation',
                        'maxlength': 1000
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel it',
                    cancelButtonText: 'Keep transaction',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true,
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
