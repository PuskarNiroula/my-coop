<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\RegularSavingAccount as Saving;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateSavingsAccounts extends Component
{
    public array $rows = [
        ['member_id' => '', 'balance' => ''],
    ];


    public function addRow(): void
    {
        foreach ($this->rows as $index => $row) {
            if (empty($row['member_id'])) {
                $this->addError("rows.$index.member_id", 'Please select a member before adding another row.');
                return;
            }

            if (!isset($row['balance']) || $row['balance'] === '' || !is_numeric($row['balance']) || (float) $row['balance'] < 0) {
                $this->addError("rows.$index.balance", 'Please enter a valid opening balance before adding another row.');
                return;
            }
        }

        $existingMemberIds = Saving::pluck('member_id')->map(fn ($id) => (string) $id);

        $eligibleCount = Member::query()
            ->where('is_active', true)
            ->whereNotIn('member_id', $existingMemberIds)
            ->count();

        $selectedCount = collect($this->rows)
            ->pluck('member_id')
            ->filter()
            ->unique()
            ->count();

        if ($selectedCount >= $eligibleCount) {
            $this->addError('rows', 'All eligible members have been selected.');
            return;
        }

        $this->resetValidation();

        $this->rows[] = [
            'member_id' => '',
            'balance' => '',
        ];
    }


    public function removeRow(int $index): void
    {
        if (count($this->rows) > 1 && isset($this->rows[$index])) {
            unset($this->rows[$index]);
            $this->rows = array_values($this->rows);
        }
    }


    public function render()
    {
        $existingMemberIds = Saving::pluck('member_id')
            ->map(fn ($id) => (string) $id);

        $eligibleMembers = Member::query()
            ->where('is_active', true)
            ->whereNotIn('member_id', $existingMemberIds)
            ->orderBy('name')
            ->get(['member_id', 'name', 'phone']);

        return view('components.create-savings-accounts', [
            'eligibleMembers' => $eligibleMembers,
        ]);
    }
}
