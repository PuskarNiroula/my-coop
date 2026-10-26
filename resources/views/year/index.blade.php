@extends('layouts.app')

@section('title', 'Year Setup')
@section('page-title', 'Year Setup')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Year Setup</h1>
                <p>Manage financial years for your cooperative.</p>
            </div>

            <a href="{{ route('year.create') }}" class="member-add-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Add Financial Year</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('year.index') }}" method="GET" class="members-search-form">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search financial year..."
                            aria-label="Search financial years"
                        >
                        <button type="submit" class="members-search-submit">
                            Search
                        </button>
                    </div>
                </form>

                <div class="members-count">
                    {{ $years->total() }} financial years found
                </div>
            </div>

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>Financial Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($years as $year)
                        <tr>
                            <td class="member-name">
                                {{ $year->name }}
                            </td>

                            <td>
                                    <span class="member-status {{ $year->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($year->status) }}
                                    </span>
                            </td>

                            <td>
                                <form
                                    action="{{ $year->status == 'active'
            ? url('/year-setup/' . $year->getId() . '/deactivate')
            : url('/year-setup/' . $year->getId() . '/activate') }}"
                                    method="POST"
                                    class="year-status-form"
                                    data-status="{{ $year->status }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="button"
                                        class="action-btn {{ $year->status == 'active' ? 'deactivate-btn' : 'activate-btn' }}"
                                        onclick="confirmYearStatus(this)"
                                    >
                                        <i class="fa-solid {{ $year->status == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        <span>{{ $year->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
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

            <div class="members-pagination">
                {{ $years->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmYearStatus(button) {
            const form = button.closest('form');
            const status = form.dataset.status;
            const activating = status !== 'active';

            Swal.fire({
                title: activating ? 'Activate financial year?' : 'Deactivate financial year?',
                text: activating
                    ? 'Are you sure you want to activate this financial year?'
                    : 'Are you sure you want to deactivate this financial year?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: activating ? 'Yes, activate' : 'Yes, deactivate',
                cancelButtonText: 'Cancel',
                confirmButtonColor: activating ? '#198754' : '#dc3545',
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
    @vite(['resources/css/member-table.css'])
@endpush
