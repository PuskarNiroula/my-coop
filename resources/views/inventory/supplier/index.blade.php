@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Suppliers</h1>
                <p>Manage suppliers and their active status.</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('suppliers.create') }}" class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Supplier</span>
                </a>
            </div>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('suppliers.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search supplier..."
                            aria-label="Search suppliers"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('suppliers.index') }}"
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
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{++$loop->index }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>
                                @if($supplier->status)
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('suppliers.edit', $supplier->getId()) }}"
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $supplier->status ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmSupplierStatusChange(
                                                {{ $supplier->getId() }},
                                                {{ $supplier->status ? 'true' : 'false' }},
                                                @js($supplier->name)
                                            )"
                                    >
                                        <i class="fa-solid {{ $supplier->status ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $supplier->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-truck-field" aria-hidden="true"></i>
                                    </div>

                                    <strong>No suppliers found</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($suppliers, 'links'))
                <div class="members-pagination">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmSupplierStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ?"{{ route('suppliers.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('suppliers.activate',":id") }}".replace(':id',id);

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
