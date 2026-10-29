@php use Common\Enums\YesNoEnum; @endphp
@extends('layouts.app')

@section('title', 'Fiscal Years')
@section('page-title', 'Fiscal Years')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Fiscal Years</h1>
                <p>Manage fiscal years and their active status.</p>
            </div>

            <a href="{{ route('fiscal-year.create') }}" class="member-add-btn">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Add Fiscal Year</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('fiscal-year.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search fiscal year..."
                            aria-label="Search fiscal years"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('fiscal-year.index') }}"
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
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    @if($fiscalYears->total() > 0)
                        Showing {{ $fiscalYears->firstItem() }}–{{ $fiscalYears->lastItem() }} of {{ $fiscalYears->total() }} fiscal years
                    @else
                        0 fiscal years found
                    @endif
                </div>
            </div>

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Fiscal Year</th>
                        <th scope="col">Is Current</th>
                        <th scope="col">Is Closed</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($fiscalYears as $fiscalYear)
                        @php($isActive = strtoupper((string) $fiscalYear->status) === 'ACTIVE')

                        <tr>
                            <td>{{ $fiscalYear->fiscal_year_id }}</td>

                            <td class="member-name">{{ $fiscalYear->name }}</td>

                            <td>{{ $fiscalYear->is_current ? YesNoEnum::YES->value : YesNoEnum::NO->value }}</td>

                            <td>{{ $fiscalYear->is_closed ? YesNoEnum::YES->value : YesNoEnum::NO->value }}</td>

                            <td>
                                    <span class="member-status {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                            </td>

                            <td>
                                <div class="member-actions">
                                    @if(!$fiscalYear->is_current)
                                        <form
                                            action="{{ route('fiscal-year.mark-current', $fiscalYear->fiscal_year_id) }}"
                                            method="POST"
                                            class="fiscal-year-confirm-form"
                                            data-action="mark-current"
                                            data-name="{{ $fiscalYear->name }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="action-btn activate-btn"
                                                aria-label="Mark {{ $fiscalYear->name }} as current"
                                            >
                                                <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                <span>Mark as Current</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($fiscalYear->is_current && !$fiscalYear->is_closed)
                                        <form
                                            action="{{ route('fiscal-year.close', $fiscalYear->fiscal_year_id) }}"
                                            method="POST"
                                            class="fiscal-year-confirm-form"
                                            data-action="close"
                                            data-name="{{ $fiscalYear->name }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="action-btn deactivate-btn"
                                                aria-label="Close fiscal year {{ $fiscalYear->name }}"
                                            >
                                                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                                                <span>Close Fiscal Year</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form
                                        action="{{ $isActive
                                                ? route('fiscal-year.deactivate', $fiscalYear->fiscal_year_id)
                                                : route('fiscal-year.activate', $fiscalYear->fiscal_year_id) }}"
                                        method="POST"
                                        class="fiscal-year-confirm-form"
                                        data-action="{{ $isActive ? 'deactivate' : 'activate' }}"
                                        data-name="{{ $fiscalYear->name }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="action-btn {{ $isActive ? 'deactivate-btn' : 'activate-btn' }}"
                                            aria-label="{{ $isActive ? 'Deactivate' : 'Activate' }} {{ $fiscalYear->name }}"
                                        >
                                            <i class="fa-solid {{ $isActive ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                            <span>{{ $isActive ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form>
                                        <a
                                            href="{{ route('fiscal-year.edit', $fiscalYear->fiscal_year_id) }}"
                                            class="action-btn edit-btn"
                                            aria-label="Edit {{ $fiscalYear->name }}"
                                        >
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                            <span>Edit</span>
                                        </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                                    </div>

                                    <strong>No fiscal years found</strong>

                                    <span>
                                            @if(request('search'))
                                            No results for "{{ request('search') }}". Try another search term.
                                        @else
                                            Get started by adding your first fiscal year.
                                        @endif
                                        </span>

                                    @if(request('search'))
                                        <a href="{{ route('fiscal-year.index') }}" class="action-btn edit-btn">
                                            Clear search
                                        </a>
                                    @else
                                        <a href="{{ route('fiscal-year.create') }}" class="action-btn activate-btn">
                                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                            <span>Add Fiscal Year</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($fiscalYears->hasPages())
                <div class="members-pagination">
                    {{ $fiscalYears->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buildConfig = function (action, name) {
                const configs = {
                    'mark-current': {
                        title: 'Mark as Current Fiscal Year?',
                        text: `All transactions created after this change will fall under fiscal year "${name}". Do you want to continue?`,
                        icon: 'warning',
                        confirmText: 'Yes, Mark as Current',
                        color: '#198754'
                    },
                    'close': {
                        title: 'Close Fiscal Year?',
                        text: `Are you sure you want to close fiscal year "${name}"? This fiscal year will no longer be current.`,
                        icon: 'warning',
                        confirmText: 'Yes, Close Fiscal Year',
                        color: '#dc3545'
                    },
                    'activate': {
                        title: 'Activate Fiscal Year?',
                        text: `Are you sure you want to activate fiscal year "${name}"?`,
                        icon: 'question',
                        confirmText: 'Yes, Activate',
                        color: '#198754'
                    },
                    'deactivate': {
                        title: 'Deactivate Fiscal Year?',
                        text: `Are you sure you want to deactivate fiscal year "${name}"?`,
                        icon: 'warning',
                        confirmText: 'Yes, Deactivate',
                        color: '#dc3545'
                    }
                };

                return configs[action] || null;
            };

            document.querySelectorAll('.fiscal-year-confirm-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmed === 'true') {
                        return;
                    }

                    const config = buildConfig(form.dataset.action, form.dataset.name);

                    if (!config) {
                        return;
                    }

                    event.preventDefault();

                    Swal.fire({
                        title: config.title,
                        text: config.text,
                        icon: config.icon,
                        showCancelButton: true,
                        confirmButtonText: config.confirmText,
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: config.color,
                        reverseButtons: true,
                        focusCancel: true
                    }).then(function (result) {
                        if (!result.isConfirmed) {
                            return;
                        }

                        form.dataset.confirmed = 'true';
                        form.submit();
                    });
                });
            });
        });
    </script>
@endpush

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
