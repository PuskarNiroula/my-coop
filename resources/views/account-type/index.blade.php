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
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Add Account Type</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('account-types.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search account type..."
                            aria-label="Search account types"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('account-types.index') }}"
                                class="search-clear"
                                aria-label="Clear search"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="members-search-submit">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <span>Search</span>
                    </button>
                </form>

                <div class="members-count">
                    <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                    @if($accountTypes->total() > 0)
                        Showing {{ $accountTypes->firstItem() }}–{{ $accountTypes->lastItem() }} of {{ $accountTypes->total() }} account types
                    @else
                        0 account types found
                    @endif
                </div>
            </div>

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th scope="col">Account Type</th>
                        <th scope="col">Interest Rate</th>
                        <th scope="col">Interest Calculation Frequency</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($accountTypes as $accountType)
                        @php($isActive = $accountType->status === 'active')

                        <tr>
                            <td class="member-name">
                                {{ $accountType->name }}
                            </td>

                            <td>
                                <span class="rate-badge">{{ number_format((float) $accountType->interest_rate, 2) }}%</span>
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', (string) $accountType->interest_payout_frequency)) }}
                            </td>

                            <td>
                                <span class="member-status {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('account-types.edit', $accountType->account_type_id) }}"
                                        class="action-btn edit-btn"
                                        aria-label="Edit {{ $accountType->name }}"
                                    >
                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form
                                        action="{{ $isActive
                                            ? route('account-types.deactivate', $accountType->account_type_id)
                                            : route('account-types.activate', $accountType->account_type_id) }}"
                                        method="POST"
                                        @if($isActive)
                                            onsubmit="return confirm('Deactivate this account type?');"
                                        @endif
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="action-btn {{ $isActive ? 'deactivate-btn' : 'activate-btn' }}"
                                            aria-label="{{ $isActive ? 'Deactivate' : 'Activate' }} {{ $accountType->name }}"
                                        >
                                            <i class="fa-solid {{ $isActive ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                            <span>{{ $isActive ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                                    </div>

                                    <strong>No account types found</strong>

                                    <span>
                                        @if(request('search'))
                                            No results for "{{ request('search') }}". Try another search term.
                                        @else
                                            Get started by adding your first account type.
                                        @endif
                                    </span>

                                    @if(request('search'))
                                        <a href="{{ route('account-types.index') }}" class="action-btn edit-btn">Clear search</a>
                                    @else
                                        <a href="{{ route('account-types.create') }}" class="action-btn activate-btn">
                                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                            <span>Add Account Type</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($accountTypes->hasPages())
                <div class="members-pagination">
                    {{ $accountTypes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
