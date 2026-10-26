@extends('layouts.app')

@section('title', 'Savings Dashboard')
@section('page-title', 'Savings Dashboard')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Savings Overview.</h1>
            <p>Track member deposits, withdrawals and cooperative savings.</p>
        </div>

        <a href="{{ url('/savings/create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Record Savings
        </a>
    </section>

    <section class="dashboard-content">

        <div class="dashboard-grid">
            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Savings Balance</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18M16 14h2"/>
                        </svg>
                    </span>
                </div>
                <strong>Rs. {{ number_format($totalSavings ?? 0, 2) }}</strong>
                <p>Current member savings balance</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Deposits</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 19V5M5 12l7-7 7 7"/>
                        </svg>
                    </span>
                </div>
                <strong>Rs. {{ number_format($totalDeposits ?? 0, 2) }}</strong>
                <p>Total savings deposited</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Withdrawals</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12l7 7 7-7"/>
                        </svg>
                    </span>
                </div>
                <strong>Rs. {{ number_format($totalWithdrawals ?? 0, 2) }}</strong>
                <p>Total savings withdrawn</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Members with Savings</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </span>
                </div>
                <strong>{{ number_format($membersWithSavings ?? 0) }}</strong>
                <p>Members with savings accounts</p>
            </article>
        </div>

        <section class="activity-card">
            <div class="activity-heading">
                <div>
                    <h3>Recent Savings Transactions</h3>
                    <p>Review the latest deposits and withdrawals.</p>
                </div>
                <a href="{{ url('/savings') }}" class="btn-primary">View All</a>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Member</th>
                        <th>Transaction Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentSavings ?? [] as $saving)
                        <tr>
                            <td>
                                {{ $saving->member->name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ ucfirst($saving->transaction_type ?? $saving->type ?? 'Deposit') }}
                            </td>
                            <td>
                                Rs. {{ number_format($saving->amount ?? 0, 2) }}
                            </td>
                            <td>
                                {{ $saving->created_at ? $saving->created_at->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td>
                                {{ ucfirst($saving->status ?? 'Completed') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No savings transactions found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="activity-card">
            <div class="activity-heading">
                <div>
                    <h3>Quick Access</h3>
                    <p>Manage daily savings operations.</p>
                </div>
            </div>

            <div class="quick-access-grid">
                <a href="{{ url('/savings') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18M16 14h2"/>
                        </svg>
                    </span>
                    <span>All Savings</span>
                    <span class="quick-access-arrow">→</span>
                </a>

                <a href="{{ url('/savings/create') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </span>
                    <span>Record Deposit</span>
                    <span class="quick-access-arrow">→</span>
                </a>

                <a href="{{ url('/members') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                        </svg>
                    </span>
                    <span>Members</span>
                    <span class="quick-access-arrow">→</span>
                </a>

                <a href="{{ url('/savings/reports') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19V5M4 19h17"/>
                            <path d="m7 15 4-4 3 2 6-7"/>
                        </svg>
                    </span>
                    <span>Savings Reports</span>
                    <span class="quick-access-arrow">→</span>
                </a>
            </div>
        </section>
    </section>
@endsection
