@extends('layouts.app')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Payment Methods</h1>
                <p>Manage payment methods and their active status.</p>
            </div>

            <a href="" class="member-add-btn">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Add Payment Method</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('payment-setup.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search payment method..."
                            aria-label="Search payment methods"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('payment-method.index') }}"
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
            </div>

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td colspan="5" class="empty-members">
                            <div class="empty-members-content">
                                <div class="empty-members-icon">
                                    <i class="fa-solid fa-money-bill-wave" aria-hidden="true"></i>
                                </div>

                                <strong>No payment methods found</strong>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
