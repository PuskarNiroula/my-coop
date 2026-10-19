<div class="members-page">
    <div class="members-heading">
        <div>
            <h1>Savings Accounts</h1>
            <p>Manage cooperative savings accounts and member balances.</p>
        </div>

        <a href="{{ url('/savings/accounts/create') }}" class="member-add-btn">
            <i class="fa-solid fa-piggy-bank"></i>
            <span>Open Savings Account</span>
        </a>
    </div>

    <div class="members-card">
        <div class="members-toolbar">
            <div class="members-search">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search member name, or phone"
                    aria-label="Search savings accounts"
                >

                <button
                    type="button"
                    wire:click="clearSearch"
                    class="search-clear"
                    aria-label="Clear search"
                    @if ($search === '') hidden @endif
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="members-count">
                <span wire:loading.remove wire:target="search,clearSearch">
                    {{ $savings->total() }} accounts found
                </span>

                <span wire:loading wire:target="search,clearSearch">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Searching...
                </span>
            </div>
        </div>

        <div class="members-table-wrapper">
            <table class="members-table">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Account Number</th>
                    <th>Member Name</th>
                    <th>Phone</th>
                    <th>Balance</th>
                    <th>Opened Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($savings as $saving)
                    <tr wire:key="saving-{{ $saving->saving_id }}">
                        <td>{{ $savings->firstItem() + $loop->index }}</td>

                        <td class="member-name">
                            {{ $saving->account_number }}
                        </td>

                        <td>
                            {{ $saving->member->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $saving->member->phone ?? 'N/A' }}
                        </td>

                        <td>
                            Rs. {{ number_format($saving->balance ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $saving->created_at?->format('Y-m-d') ?? 'N/A' }}
                        </td>

                        <td>
                                <span class="member-status {{ strtolower($saving->status ?? 'active') === 'active' ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($saving->status ?? 'Active') }}
                                </span>
                        </td>

                        <td>
                            <div class="member-actions">
                                <a
                                    href="{{ url('/savings/' . $saving->saving_id) }}"
                                    class="action-btn edit-btn"
                                    title="View savings account"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                    <span>View</span>
                                </a>

                                @if (strtolower($saving->status ?? 'active') === 'active')
                                    <button
                                        type="button"
                                        wire:click="confirmClose({{ $saving->saving_id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmClose"
                                        class="action-btn deactivate-btn"
                                        title="Close savings account"
                                    >
                                        <i class="fa-solid fa-lock"></i>
                                        <span>Close Account</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-members">
                            <div class="empty-members-content">
                                <div class="empty-members-icon">
                                    <i class="fa-solid fa-piggy-bank"></i>
                                </div>

                                <strong>No savings accounts found</strong>

                                <span>
                                        Try another search term or open a new savings account.
                                    </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="members-pagination">
            {{ $savings->links() }}
        </div>
    </div>
</div>

@script
<script>
    $wire.on('confirm-close-saving', (event) => {
        Swal.fire({
            title: 'Close Savings Account?',
            text: 'Are you sure you want to close this savings account? You will be redirected to the account closing page.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Close Account',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'member-swal-popup',
                title: 'member-swal-title',
                htmlContainer: 'member-swal-text',
                confirmButton: 'member-swal-confirm member-swal-deactivate',
                cancelButton: 'member-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/savings/close/${encodeURIComponent(event.savingId)}`;
            }
        });
    });
</script>
@endscript
