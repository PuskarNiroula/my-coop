
@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Products</h1>
                <p>Manage products and their active status.</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('products.create') }}" class="member-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Product</span>
                </a>
            </div>
        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('products.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search product..."
                            aria-label="Search products"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('products.index') }}"
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
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Base Unit</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ ++ $loop->index }}</td>

                            <td>
                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;"
                                    >
                                @else
                                    <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 6px;">
                                        <i class="fa-solid fa-box" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </td>

                            <td>{{ $product->name }}</td>
                            <td>{{ $product->type->name }}</td>
                            <td>{{ $product->baseUnit->name ?? 'N/A' }}</td>
                            <td>{{ $product->description ?? 'N/A' }}</td>

                            <td>
                                @if($product->status === 'active')
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="member-actions">
                                    <a
                                        href="{{ route('products.edit', $product->getId()) }}"
                                        class="member-edit-btn btn-primary"
                                    >
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="{{ $product->status === 'active' ? 'btn-danger' : 'btn-activate' }}"
                                        onclick="confirmProductStatusChange(
                                                {{ $product->getId() }},
                                                {{ $product->status === 'active' ? 'true' : 'false' }},
                                                @js($product->name)
                                            )"
                                    >
                                        <i class="fa-solid {{ $product->status === 'active' ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                                    </div>

                                    <strong>No products found</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($products, 'links'))
                <div class="members-pagination">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmProductStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ? "{{ route('products.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('products.activate', ':id') }}".replace(':id', id);

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
