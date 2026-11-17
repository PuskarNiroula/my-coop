
@extends('layouts.app')

@section('title', 'Purchases')
@section('page-title', 'Purchases')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Purchases</h1>
                <p>Manage purchases, purchase returns and their status.</p>
            </div>

        </div>

        <div class="members-card">
            <div class="members-toolbar">
                <form action="{{ route('purchases.index') }}" method="GET" class="members-search-form" role="search">
                    <div class="members-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search purchase or supplier..."
                            aria-label="Search purchases"
                            autocomplete="off"
                        >

                        @if(request('search'))
                            <a
                                href="{{ route('purchases.index') }}"
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
                        <th scope="col">Date (Nepali)</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Paid Amount</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchases->firstItem() + $loop->index }}</td>

                            <td>{{ $purchase->nepali_date ?? 'N/A' }}</td>

                            <td>{{ number_format($purchase->amount, 2) }}</td>

                            <td>{{ number_format($purchase->discount ?? 0, 2) }}</td>

                            <td>
                                @if($purchase->transaction_type === 'purchase')
                                    <span class="rate-badge">Purchase</span>
                                @else
                                    <span class="gender-badge">Purchase Return</span>
                                @endif
                            </td>

                            <td>{{ number_format($purchase->paid_amount ?? 0, 2) }}</td>

                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>

                            <td>
                                @if($purchase->status === 'active')
                                    <span class="member-status status-active">
                                            <span class="status-dot"></span>
                                            Active
                                        </span>
                                @else
                                    <span class="member-status status-inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                @endif
                            </td>

                            <td>
                                <div class="member-actions">
                                    <button
                                        type="button"
                                        class="action-btn {{ $purchase->status === 'active' ? 'deactivate-btn' : 'activate-btn' }}"
                                        onclick="confirmPurchaseStatusChange(
                                                {{ $purchase->getId() }},
                                                {{ $purchase->status === 'active' ? 'true' : 'false' }},
                                                @js($purchase->supplier->name ?? 'this purchase')
                                            )"
                                    >
                                        <i class="fa-solid {{ $purchase->status === 'active' ? 'fa-ban' : 'fa-check' }}" aria-hidden="true"></i>
                                        {{ $purchase->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-members">
                                <div class="empty-members-content">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                                    </div>

                                    <strong>No purchases found</strong>
                                    <span>No purchase records match your search.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($purchases, 'links'))
                <div class="members-pagination">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmPurchaseStatusChange(id, isActive, name) {
            const action = isActive ? 'deactivate' : 'activate';

            const url = isActive
                ? "{{ route('purchases.deactivate', ':id') }}".replace(':id', id)
                : "{{ route('purchases.activate', ':id') }}".replace(':id', id);

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
