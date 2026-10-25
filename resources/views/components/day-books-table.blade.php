<div class="members-page">
    <div class="members-heading">
        <div>
            <h1>Day Books</h1>
            <p>Manage business days, close daily operations, and view transactions.</p>
        </div>

        <a href="{{ url('/day-books/create') }}" class="member-add-btn">
            <i class="fa-solid fa-book-open"></i>
            <span>Create New Day Book</span>
        </a>
    </div>

    <div class="members-card">
        <div class="members-toolbar">
            <div class="members-search">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search Nepali date (e.g. 2083-07)"
                    aria-label="Search day books by Nepali date"
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
                    {{ $dayBooks->total() }} day books found
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
                    <th>Day Book ID</th>
                    <th>English Date</th>
                    <th>Nepali Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($dayBooks as $dayBook)
                    <tr wire:key="day-book-{{ $dayBook->day_book_id }}">
                        <td>
                            {{ $dayBooks->firstItem() + $loop->index }}
                        </td>

                        <td class="member-name">
                            #{{ $dayBook->day_book_id }}
                        </td>

                        <td>
                            {{ $dayBook->date_english?->format('Y-m-d') ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $dayBook->date_nepali }}
                        </td>

                        <td>
                                <span class="member-status {{ strtolower($dayBook->status) === 'open' ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($dayBook->status) }}
                                </span>
                        </td>

                        <td>
                            <div class="member-actions">
                                <a
                                    href="{{ url('/day-books/' . $dayBook->day_book_id . '/transactions') }}"
                                    class="action-btn edit-btn"
                                    title="View day book transactions"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Transactions</span>
                                </a>

                                @if ($dayBook->status === 'open')
                                    <button
                                        type="button"
                                        wire:click="confirmClose({{ $dayBook->day_book_id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmClose"
                                        class="action-btn deactivate-btn"
                                        title="Close day book"
                                    >
                                        <i class="fa-solid fa-lock"></i>
                                        <span>Close Day</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-members">
                            <div class="empty-members-content">
                                <div class="empty-members-icon">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>

                                <strong>No day books found</strong>

                                <span>
                                        @if ($search !== '')
                                        Try another Nepali date.
                                    @else
                                        Create a new day book to get started.
                                    @endif
                                    </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($dayBooks->hasPages())
            <div class="members-pagination">
                {{ $dayBooks->links() }}
            </div>
        @endif
    </div>
</div>

@script
<script>
    $wire.on('confirm-close-day-book', (event) => {
        Swal.fire({
            title: 'Close Day Book?',
            text: 'Are you sure you want to close this business day? New transactions should no longer be allowed for this day.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Close Day',
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
                $wire.closeDayBook(event.dayBookId);
            }
        });
    });

    $wire.on('day-book-updated', () => {
        Swal.fire({
            title: 'Day Book Closed',
            text: 'The day book has been closed successfully.',
            icon: 'success',
            buttonsStyling: false,
            customClass: {
                popup: 'member-swal-popup',
                title: 'member-swal-title',
                htmlContainer: 'member-swal-text',
                confirmButton: 'member-swal-confirm'
            }
        });
    });
</script>
@endscript
