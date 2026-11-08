@extends('layouts.app')

@section('title', 'Unit Categories')
@section('page-title', 'Unit Categories')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Unit Categories</h1>
                <p>Manage measurement categories and their base units.</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Category</span>
                </a>
            </div>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('inventory.category.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search category..."
                            aria-label="Search categories"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('inventory.category.index') }}"
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
                        <th scope="col">Base Unit</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ ++$loop->index }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->unit->name }} ({{ $category->unit->symbol }})</td>
                            <td>
                                @if($category->status)
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="member-actions">
                                    <a
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $category->status ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmCategoryStatusChange(
                                            {{ $category->getId() }},
                                            {{ $category->status ? 'true' : 'false' }},
                                            @js($category->name)
                                        )"
                                    >
                                        <i class="fa-solid {{ $category->status ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $category->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                                    </div>

                                    <strong>No unit categories found</strong>
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
        function confirmCategoryStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';
            const url="";

            {{--const url = isActive--}}
            {{--    ? "{{ route('inventory.unit-categories.deactivate', ':id') }}".replace(':id', id)--}}
            {{--    : "{{ route('inventory.unit-categories.activate', ':id') }}".replace(':id', id);--}}

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
