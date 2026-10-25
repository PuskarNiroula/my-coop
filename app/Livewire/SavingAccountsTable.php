<?php

namespace App\Livewire;

use App\Models\RegularSavingAccount;
use Livewire\Component;
use Livewire\WithPagination;

class SavingAccountsTable extends Component
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

    public function confirmClose($savingId): void
    {
        $this->dispatch(
            'confirm-close-saving',
            savingId: $savingId
        );
    }

    public function render()
    {
        $savings = RegularSavingAccount::query()
            ->with('member')
            ->when(trim($this->search) !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('member', function ($memberQuery) use ($search) {
                            $memberQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('regular_saving_id')
            ->paginate(10);

        return view('components.saving-accounts-table', [
            'savings' => $savings,
        ]);
    }
}
