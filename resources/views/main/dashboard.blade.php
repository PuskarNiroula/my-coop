
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Good afternoon, {{ auth()->user()->name ?? 'Administrator' }}.</h1>
            <p>Here's an overview of your cooperative's activities.</p>
        </div>

        <a href="{{ url('/members/create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Add New Member
        </a>
    </section>

    <section class="dashboard-content">
        <div class="dashboard-welcome">
            <div>
                <span class="dashboard-eyebrow">JAY DURGA KRISHI SAHAKARI SANSTHA</span>
                <h2>Growing together.</h2>
                <p>Manage members, savings, loans, shares and daily operations from one place.</p>
            </div>
            <div class="welcome-icon">
                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M24 40V22M24 30C12 30 8 22 8 14C19 14 24 22 24 30ZM24 23C24 13 31 7 40 7C40 17 34 23 24 23Z"/>
                    <path d="M14 41H34"/>
                </svg>
            </div>
        </div>

        <div class="dashboard-grid">
            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Members</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </span>
                </div>
                <strong>{{ number_format($totalMembers ?? 0) }}</strong>
                <p>Registered members</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Savings</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18M16 14h2"/>
                        </svg>
                    </span>
                </div>
                <strong>Rs. {{ number_format($totalSavings ?? 0, 2) }}</strong>
                <p>Total savings balance</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Active Loans</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </span>
                </div>
                <strong>{{ number_format($activeLoans ?? 0) }}</strong>
                <p>Currently active loans</p>
            </article>

            <article class="dashboard-card">
                <div class="dashboard-card-top">
                    <span>Total Expenses</span>
                    <span class="dashboard-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 3h16v18l-4-2-4 2-4-2-4 2V3Z"/>
                            <path d="M8 8h8M8 12h8M8 16h4"/>
                        </svg>
                    </span>
                </div>
                <strong>Rs. {{ number_format($totalExpenses ?? 0, 2) }}</strong>
                <p>Total recorded expenses</p>
            </article>
        </div>

        <section class="activity-card">
            <div class="activity-heading">
                <div>
                    <h3>Quick Access</h3>
                    <p>Jump directly to your daily operations.</p>
                </div>
            </div>

            <div class="quick-access-grid">
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

                <a href="{{ url('/savings') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18M16 14h2"/>
                        </svg>
                    </span>
                    <span>Savings</span>
                    <span class="quick-access-arrow">→</span>
                </a>

                <a href="{{ url('/loans') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </span>
                    <span>Loans</span>
                    <span class="quick-access-arrow">→</span>
                </a>

                <a href="{{ url('/inventory') }}" class="quick-access-item">
                    <span class="quick-access-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round">
                            <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                            <path d="M3 8v9l9 5 9-5V8M12 13v9"/>
                        </svg>
                    </span>
                    <span>Inventory</span>
                    <span class="quick-access-arrow">→</span>
                </a>
            </div>
        </section>
    </section>
@endsection
