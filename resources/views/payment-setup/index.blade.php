
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

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('payment-setup.transfer') }}" class="member-add-btn">
                    <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                    <span>Transfer Balance</span>
                </a>

                <a href="{{ route('payment-setup.create') }}" class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Payment Method</span>
                </a>
            </div>
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
                                href="{{ route('payment-setup.index') }}"
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
                        <th scope="col">Balance</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($paymentMethods as $pm)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pm->name }}</td>
                            <td>{{ $pm->balance }}</td>
                            <td>
                                @if($pm->status == "active")
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('payment-setup.edit', $pm->getId()) }}"
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $pm->status == 'active' ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmStatusChange(
                                            {{ $pm->getId() }},
                                            {{ $pm->status == 'active' ? 'true' : 'false' }},
                                            @js($pm->name)
                                        )"
                                    >
                                        <i class="fa-solid {{ $pm->status == 'active' ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $pm->status == 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
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
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ? "{{ route('payment-setup.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('payment-setup.activate', ':id') }}".replace(':id', id);

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
