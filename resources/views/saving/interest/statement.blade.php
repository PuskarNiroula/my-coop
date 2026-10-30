@extends('layouts.app')

@section('content')
    @php
        $statements = collect($reportDto->statements);
        $totalDeposits = $statements->sum('totalDeposits');
        $totalWithdrawals = $statements->sum('totalWithdrawals');
        $totalBalance = $statements
            ->groupBy('memberName')
            ->sum(function ($memberStatements) {
                return $memberStatements->last()->closingBalance;
            });
    @endphp

    <div class="semi-annual-report ">
        <div class="report-heading">
            <div class="report-heading-content">
                <span class="report-eyebrow">Savings Report</span>
                <h1>Semi Annual Savings Statement</h1>
                <p>Member-wise opening balance, deposits, withdrawals and closing balance for the selected period.</p>
            </div>

            <button type="button" class="report-print-btn" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Report
            </button>
        </div>

        <div class="report-summary">
            <div class="report-summary-card deposit">
                <div class="report-summary-top">
                    <span class="report-summary-label">Total Savings</span>
                    <span class="report-summary-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="19" x2="12" y2="5"></line>
                            <polyline points="5 12 12 5 19 12"></polyline>
                        </svg>
                    </span>
                </div>
                <strong class="report-summary-value">{{ number_format($totalDeposits, 2) }}</strong>
                <p class="report-summary-description">Total amount deposited by all members</p>
            </div>

            <div class="report-summary-card withdrawal">
                <div class="report-summary-top">
                    <span class="report-summary-label">Total Withdrawals</span>
                    <span class="report-summary-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <polyline points="19 12 12 19 5 12"></polyline>
                        </svg>
                    </span>
                </div>
                <strong class="report-summary-value">{{ number_format($totalWithdrawals, 2) }}</strong>
                <p class="report-summary-description">Total amount withdrawn by all members</p>
            </div>

            <div class="report-summary-card balance">
                <div class="report-summary-top">
                    <span class="report-summary-label">Total Balance</span>
                    <span class="report-summary-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                    </span>
                </div>
                <strong class="report-summary-value">{{ number_format($totalBalance, 2) }}</strong>
                <p class="report-summary-description">Combined latest closing balance of all members</p>
            </div>
        </div>

        <div class="report-table-card">
            <div class="report-table-header">
                <div>
                    <h2>Statement Details</h2>
                    <p>Month-wise savings movement per member</p>
                </div>
                <span class="report-record-count">{{ $statements->count() }} Records</span>
            </div>

            <div class="report-table-wrapper">
                <table class="report-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Member Name</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Fiscal Year</th>
                        <th class="text-end">Opening Balance</th>
                        <th class="text-end">Deposits</th>
                        <th class="text-end">Withdrawals</th>
                        <th class="text-end">Closing Balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($reportDto->statements as $index => $statement)
                        <tr>
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td class="report-member-name">{{ $statement->memberName }}</td>
                            <td class="report-month">{{ $statement->month }}</td>
                            <td>{{ $statement->year }}</td>
                            <td>{{ $statement->fiscalYear }}</td>
                            <td class="text-end report-amount">{{ number_format($statement->openingBalance, 2) }}</td>
                            <td class="text-end report-amount report-deposit">{{ number_format($statement->totalDeposits, 2) }}</td>
                            <td class="text-end report-amount report-withdrawal">{{ number_format($statement->totalWithdrawals, 2) }}</td>
                            <td class="text-end report-amount report-closing-balance">{{ number_format($statement->closingBalance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="report-empty">No statement records found.</td>
                        </tr>
                    @endforelse
                    </tbody>

                    @if($statements->count())
                        <tfoot>
                        <tr>
                            <td colspan="6" class="text-end">Grand Total</td>
                            <td class="text-end report-amount report-deposit">{{ number_format($totalDeposits, 2) }}</td>
                            <td class="text-end report-amount report-withdrawal">{{ number_format($totalWithdrawals, 2) }}</td>
                            <td class="text-end report-amount report-closing-balance">{{ number_format($totalBalance, 2) }}</td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <div class="report-footer-note">
                <span><strong>Note:</strong> Total balance is calculated from the latest closing balance of each member.</span>
            </div>
        </div>

        <div class="report-action-card">
            <div class="report-action-content">
                <h3>Proceed to Interest Calculation</h3>
                <p>Review the statement above carefully. Once confirmed, interest will be calculated based on these balances.</p>
            </div>

            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to proceed with interest calculation?');">
                @csrf
                <button type="button"
                        class="report-proceed-btn"
                        onclick="confirmInterestCalculation()"
                    @disabled(!$statements->count())>
                    Proceed to Interest Calculation
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/semi-annual-statement.css')
@endpush
@push('scripts')
    <script>
        function confirmInterestCalculation() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to proceed with interest calculation?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#246344',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/savings/interest/calculate';
                }
            });
        }
    </script>
@endpush
