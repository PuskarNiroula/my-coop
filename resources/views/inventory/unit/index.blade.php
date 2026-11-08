@extends('layouts.app')

@section('title', 'Units')
@section('page-title', 'Units')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Units</h1>
                <p>Manage measurement units and their active status.</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('inventory.units.create') }}" class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Unit</span>
                </a>
            </div>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('inventory.units.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search unit..."
                            aria-label="Search units"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('inventory.units.index') }}"
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
                        <th scope="col">Symbol</th>
                        <th scope="col">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $units->firstItem() + $loop->index }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->symbol }}</td>
                            <td>{{ $unit->category->name ?? 'N/A' }}</td>
                            <td>
                                @if($unit->status)
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('inventory.units.edit', $unit->getId()) }}"
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $unit->status ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmUnitStatusChange(
                                                {{ $unit->getId() }},
                                                {{ $unit->status ? 'true' : 'false' }},
                                                @js($unit->name)
                                            )"
                                    >
                                        <i class="fa-solid {{ $unit->status ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $unit->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                                    </div>

                                    <strong>No units found</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($units, 'links'))
                <div class="members-pagination">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmUnitStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ? "{{ route('inventory.units.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('inventory.units.activate', ':id') }}".replace(':id', id);

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
