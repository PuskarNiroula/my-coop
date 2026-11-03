@extends('layouts.app')

@section('content')

    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Expense Heads</h1>
                <p>Manage expense categories, update details, and control their status.</p>
            </div>

            <a href="{{ route("expenses.create")}}" class="member-add-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Create Expense Head</span>
            </a>
        </div>

        <div class="members-card">

            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>Expense Head Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($expenseHeads as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="member-name">
                                {{ $expense->name }}
                            </td>

                            <td>
                                <span class="member-status {{ strtolower($expense->status) === 'active' ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="member-actions">

                                    <a
                                        href="{{ route('expenses.edit',$expense->getId()) }}"
                                        class="action-btn btn-primary"
                                        title="Edit expense head"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form method="POST" action="{{ strtolower($expense->status) === 'active'? route('expenses.deactivate', $expense->getId()) : route('expenses.activate', $expense->getId()) }}" class="status-form">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="button"
                                            class="btn-danger {{ strtolower($expense->status) === 'active' ? 'deactivate-btn' : 'edit-btn' }} status-toggle-btn"
                                            data-status="{{ strtolower($expense->status) }}"
                                            data-name="{{ $expense->name }}"
                                        >
                                            <i class="fa-solid {{ strtolower($expense->status) === 'active' ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            <span>{{ strtolower($expense->status) === 'active' ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>

                                    <strong>No expense heads found</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if ($expenseHeads->hasPages())
                <div class="members-pagination">
                    {{ $expenseHeads->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.status-toggle-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const status = this.dataset.status;
                const name = this.dataset.name;

                const isActive = status === 'active';

                Swal.fire({
                    title: isActive ? 'Deactivate Expense Head?' : 'Activate Expense Head?',
                    text: `Are you sure you want to ${isActive ? 'deactivate' : 'activate'} "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: isActive ? '#d33' : '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: isActive ? 'Yes, Deactivate' : 'Yes, Activate',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    @vite(['resources/css/expense.css'])
@endpush
