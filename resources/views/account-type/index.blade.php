@extends('layouts.app')

@section('title', 'Account Types')
@section('page-title', 'Account Types')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Account Types.</h1>
            <p>Manage savings account types, interest rates and payout frequencies.</p>
        </div>

        <a href="{{ route('account-types.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Add Account Type
        </a>
    </section>

    <section class="dashboard-content">
        <div class="dashboard-welcome">
            <div>
                <span class="dashboard-eyebrow">JAY DURGA KRISHI SAHAKARI SANSTHA</span>
                <h2>Account types and interest.</h2>
                <p>View and manage the account types offered to cooperative members.</p>
            </div>

            <div class="welcome-icon">
                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="6" y="13" width="36" height="25" rx="4"/>
                    <path d="M6 20h36M30 29h5"/>
                    <circle cx="24" cy="29" r="5"/>
                    <path d="M15 7h18"/>
                </svg>
            </div>
        </div>

        <section class="activity-card">
            <div class="activity-heading">
                <div>
                    <h3>Account Type List</h3>
                    <p>Review account types and change their activation status.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Account Type</th>
                        <th>Interest Rate</th>
                        <th>Interest Payout Frequency</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($accountTypes as $accountType)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $accountType->name }}</td>
                            <td>{{ number_format((float) $accountType->interest_rate, 2) }}%</td>
                            <td>{{ ucwords(str_replace('_', ' ', $accountType->interest_payout_frequency)) }}</td>
                            <td>
                                    <span class="status-badge {{ $accountType->status ? 'status-active' : 'status-inactive' }}">
                                        {{ $accountType->status ? 'Active' : 'Inactive' }}
                                    </span>
                            </td>
                            <td>
                                <form action="{{ route('account-types.toggle-status', $accountType->account_type_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="{{ $accountType->status ? 'btn-secondary' : 'btn-primary' }}">
                                        {{ $accountType->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No account types found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($accountTypes, 'links'))
                <div class="pagination-wrapper">
                    {{ $accountTypes->links() }}
                </div>
            @endif
        </section>
    </section>
@endsection

@push('styles')
 @vite('resources/css/account_type.css')
@endpush
