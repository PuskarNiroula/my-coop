<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SavingAccountsTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection(): Collection
    {
        return Member::query()
            ->whereDoesntHave('savingAccounts')
            ->select('members.member_id', 'members.name', 'members.phone')
            ->get()
            ->map(function ($member) {
                return [
                    $member->member_id,
                    $member->name,
                    $member->phone,
                    null,
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Phone', 'Balance'];
    }
}
