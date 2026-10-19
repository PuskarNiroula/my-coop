<div class="members-page">
    <div class="members-heading">
        <div>
            <h1>Members</h1>
            <p>Manage and search your cooperative members.</p>
        </div>

        <a href="{{ route('member.create') }}" class="member-add-btn">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add Member</span>
        </a>
    </div>

    <div class="members-card">
        <div class="members-toolbar">
            <div class="members-search">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Search name, phone, or citizenship number..."
                    aria-label="Search members"
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
                    {{ $members->total() }} members found
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
                    <th>Member Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Citizenship Number</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($members as $member)
                    <tr wire:key="member-{{ $member->id }}">
                        <td>{{ $members->firstItem() + $loop->index }}</td>

                        <td class="member-name">
                            {{ $member->name }}
                        </td>

                        <td>{{ $member->phone }}</td>

                        <td>
                                <span class="gender-badge">
                                    {{ ucfirst($member->gender) }}
                                </span>
                        </td>

                        <td>{{ $member->citizenship_number }}</td>

                        <td>{{ $member->address }}</td>

                        <td>
                                <span class="member-status {{ $member->is_active ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </span>
                        </td>

                        <td>
                            <div class="member-actions">
                                <a
                                    href="{{ route('member.edit', $member->member_id) }}"
                                    class="action-btn edit-btn"
                                    title="Edit member"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </a>

                                @if ($member->is_active)
                                    <button
                                        type="button"
                                        wire:click="confirmDeactivate({{ $member->member_id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmDeactivate"
                                        class="action-btn deactivate-btn"
                                        title="Deactivate member"
                                    >
                                        <i class="fa-solid fa-user-slash"></i>
                                        <span>Deactivate</span>
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="confirmActivate({{ $member->member_id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmActivate"
                                        class="action-btn activate-btn"
                                        title="Activate member"
                                    >
                                        <i class="fa-solid fa-user-check"></i>
                                        <span>Activate</span>
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
                                    <i class="fa-solid fa-users"></i>
                                </div>

                                <strong>No members found</strong>

                                <span>
                                        Try another search term or add a new member.
                                    </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="members-pagination">
            {{ $members->links() }}
        </div>
    </div>
</div>

@script
<script>
    $wire.on('confirm-member-status', (event) => {
        const isActivate = event.action === 'activate';

        Swal.fire({
            title: isActivate ? 'Activate Member?' : 'Deactivate Member?',
            text: isActivate
                ? 'Are you sure you want to activate this member?'
                : 'Are you sure you want to deactivate this member?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: isActivate ? 'Yes, Activate' : 'Yes, Deactivate',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'member-swal-popup',
                title: 'member-swal-title',
                htmlContainer: 'member-swal-text',
                confirmButton: isActivate
                    ? 'member-swal-confirm member-swal-activate'
                    : 'member-swal-confirm member-swal-deactivate',
                cancelButton: 'member-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('change-member-status', {
                    memberId: event.memberId,
                    action: event.action
                });
            }
        });
    });

    $wire.on('member-status-updated', (event) => {
        Swal.fire({
            title: 'Success!',
            text: event.message,
            icon: 'success',
            timer: 1800,
            showConfirmButton: false
        });
    });
</script>
@endscript

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
