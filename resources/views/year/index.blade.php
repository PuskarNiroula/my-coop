
@extends('layouts.app')

@section('title', 'Year Setup')
@section('page-title', 'Year Setup')

@section('content')
    <div class="year-setup-page">
        <div class="year-setup-heading">
            <div>
                <h1>Year Setup</h1>
                <p>Manage financial years for your cooperative.</p>
            </div>

            <a href="{{ route('year.create') }}" class="year-setup-add-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Add Financial Year</span>
            </a>
        </div>

        <div class="year-setup-card">
            <div class="year-setup-toolbar">
                <form action="{{ route('year.index') }}" method="GET" class="year-setup-search-form">
                    <div class="year-setup-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search financial year..."
                            aria-label="Search financial years"
                        >
                        <button type="submit" class="year-setup-search-btn">
                            Search
                        </button>
                    </div>
                </form>

                <div class="year-setup-count">
                    <i class="fa-solid fa-calendar-days"></i>
                    {{ $years->total() }} financial {{ $years->total() === 1 ? 'year' : 'years' }} found
                </div>
            </div>

            <div class="year-setup-table-wrapper">
                <table class="year-setup-table">
                    <thead>
                    <tr>
                        <th>Financial Year</th>
                        <th>Sequence Number</th>
                        <th>Status</th>
                        <th>Current Year</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($years as $year)
                        <tr>
                            <td class="year-setup-name">
                                {{ $year->name }}
                            </td>

                            <td>
                                    <span class="year-setup-sequence">
                                        {{ $year->sequence }}
                                    </span>
                            </td>

                            <td>
                                    <span class="year-setup-status {{ $year->status === 'active' ? 'is-active' : 'is-inactive' }}">
                                        <span class="year-setup-status-dot"></span>
                                        {{ ucfirst($year->status) }}
                                    </span>
                            </td>

                            <td>
                                @if($year->is_current)
                                    <span class="year-setup-current-badge">
                                            <i class="fa-solid fa-star"></i>
                                            Current
                                        </span>
                                @else
                                    <span class="year-setup-not-current">Not current</span>
                                @endif
                            </td>

                            <td>
                                <div class="year-setup-actions">
                                    <form
                                        action="{{ url('/year-setup/' . $year->getId() . ($year->status === 'active' ? '/deactivate' : '/activate')) }}"
                                        method="POST"
                                        class="year-setup-status-form"
                                        data-status="{{ $year->status }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="button"
                                            class="year-setup-action-btn {{ $year->status === 'active' ? 'is-deactivate' : 'is-activate' }}"
                                            onclick="confirmYearStatus(this)"
                                        >
                                            <i class="fa-solid {{ $year->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                            <span>{{ $year->status === 'active' ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form>

                                    <a
                                        href="{{ route('year.edit', $year->year_id) }}"
                                        class="year-setup-action-btn is-edit"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                        <span>Edit</span>
                                    </a>

                                    @if(!$year->is_current)
                                        <form
                                            action="{{ url('/year-setup/' . $year->year_id . '/mark-as-current') }}"
                                            method="POST"
                                            class="year-setup-current-form"
                                            data-year-name="{{ $year->name }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="button"
                                                class="year-setup-action-btn is-mark-current"
                                                onclick="confirmMarkAsCurrent(this)"
                                            >
                                                <i class="fa-solid fa-star"></i>
                                                <span>Mark as Current</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="year-setup-empty">
                                <div class="year-setup-empty-content">
                                    <div class="year-setup-empty-icon">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                    <strong>No financial years found</strong>
                                    <span>Add a financial year to get started.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($years->hasPages())
                <div class="year-setup-pagination">
                    {{ $years->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmYearStatus(button) {
            const form = button.closest('form');
            const activating = form.dataset.status !== 'active';

            Swal.fire({
                title: activating ? 'Activate financial year?' : 'Deactivate financial year?',
                text: activating
                    ? 'Are you sure you want to activate this financial year?'
                    : 'Are you sure you want to deactivate this financial year?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: activating ? 'Yes, activate' : 'Yes, deactivate',
                cancelButtonText: 'Cancel',
                confirmButtonColor: activating ? '#15803d' : '#dc2626',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmMarkAsCurrent(button) {
            const form = button.closest('form');
            const yearName = form.dataset.yearName;

            Swal.fire({
                title: 'Change current financial year?',
                text: `Are you sure you want to mark ${yearName} as the current financial year?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, make current',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#15803d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush

@push('styles')
    @vite(['resources/css/year-setup.css'])
@endpush
