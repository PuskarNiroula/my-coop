<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MembersTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $members = Member::query()
            ->when(trim($this->search) !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('citizenship_number', 'like', "%{$search}%");
                });
            })
            ->latest('member_id')
            ->paginate(10);

        return view('components.members_table', [
            'members' => $members,
        ]);
    }

    public function confirmDeactivate($memberId): void
    {
        $this->dispatch(
            'confirm-member-status',
            memberId: $memberId,
            action: 'deactivate'
        );
    }

    public function confirmActivate($memberId): void
    {
        $this->dispatch(
            'confirm-member-status',
            memberId: $memberId,
            action: 'activate'
        );
    }

    #[On('change-member-status')]
    public function changeMemberStatus($memberId, $action): void
    {
        if (!in_array($action, ['activate', 'deactivate'], true)) {
            abort(403);
        }

        $member = Member::where('member_id', $memberId)->firstOrFail();

        $newStatus = $action === 'activate';

        if ((bool) $member->is_active === $newStatus) {
            return;
        }

        $member->update([
            'is_active' => $newStatus,
        ]);
        $member->save();

        $this->dispatch(
            'member-status-updated',
            message: $newStatus
                ? 'Member activated successfully.'
                : 'Member deactivated successfully.'
        );
    }
}
