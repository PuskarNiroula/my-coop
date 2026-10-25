@extends('layouts.app')

@section('title', 'Account Types')
@section('page-title', 'Account Types')


@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Account Types</h1>
                <p>Manage savings account types, interest rates and payout frequencies.</p>
            </div>

            <a href="{{ route('account-types.create') }}" class="member-add-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Add Account Type</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('account-types.index') }}" method="GET" class="members-search-form">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search account type..."
                            aria-label="Search account types"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('account-types.index') }}"
                                class="search-clear"
                                aria-label="Clear search"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif

                        <button type="submit" class="members-search-submit">
                            Search
                        </button>
                    </div>
                </form>

                <div class="members-count">
                    {{ $accountTypes->total() }} account types found
                </div>
            </div>

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>Account Type</th>
                        <th>Interest Rate</th>
                        <th>Interest Calculation Frequency</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($accountTypes as $accountType)
                        <tr>
                            <td class="member-name">
                                {{ $accountType->name }}
                            </td>

                            <td>
                                {{ number_format((float) $accountType->interest_rate, 2) }}%
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', $accountType->interest_payout_frequency)) }}
                            </td>

                            <td>
            <span class="member-status {{ $accountType->status == 'active' ? 'status-active' : 'status-inactive' }}">
                <span class="status-dot"></span>
                {{ $accountType->status == 'active' ? 'Active' : 'Inactive' }}
            </span>
                            </td>

                            <td>
                                <div class="member-actions">
                                    <form
                                        action="{{ $accountType->status == 'active'
                        ? route('account-types.deactivate', $accountType->account_type_id)
                        : route('account-types.activate', $accountType->account_type_id) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        @method('PATCH')

                                        @if($accountType->staut=="inactive")
                                            @method('PATCH')
                                        @endif

                                        <button
                                            type="submit"
                                            class="action-btn {{ $accountType->status == 'active' ? 'deactivate-btn' : 'activate-btn' }}"
                                        >
                                            <i class="fa-solid {{ $accountType->status == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                            <span>{{ $accountType->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form> <a href="{{ route('account-types.edit',$accountType->account_type_id) }}" class="btn-secondary">Edit</a>
                                </div>
                            </td>
                        </tr>
                  @empty
                        <tr>
                            <td colspan="5" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-wallet"></i>
                                    </div>

                                    <strong>No account types found</strong>

                                    <span>
                                        Try another search term or add a new account type.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="members-pagination">
                {{ $accountTypes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
