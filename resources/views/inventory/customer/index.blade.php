@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Customers</h1>
                <p>Manage customers and their active status.</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('customers.create') }}" class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Customer</span>
                </a>
            </div>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('customers.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search customer..."
                            aria-label="Search customers"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('customers.index') }}"
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
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Credit Balance</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ ++$loop->index }}</td>

                            <td>{{ $customer->name }}</td>

                            <td>{{ $customer->phone }}</td>

                            <td>{{ $customer->address ?? 'N/A' }}</td>
                            <td>{{ number_format($customer->credit_balance, 2) }}</td>

                            <td>
                                @if($customer->status === 'active')
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('customers.edit', $customer->getId()) }}"
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $customer->status === 'active' ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmCustomerStatusChange(
                                            {{ $customer->getId() }},
                                            {{ $customer->status === 'active' ? 'true' : 'false' }},
                                            @js($customer->name)
                                        )"
                                    >
                                        <i class="fa-solid {{ $customer->status === 'active' ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-users" aria-hidden="true"></i>
                                    </div>

                                    <strong>No customers found</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($customers, 'links'))
                <div class="members-pagination">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmCustomerStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ? "{{ route('customers.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('customers.activate', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action} it!`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PATCH';

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush

@push('styles')
    @vite(['resources/css/member-table.css'])

@endpush
