<?php

namespace App\Imports;

use Member\Dto\ImportMemberDto;
use Member\Service\MemberService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use RuntimeException;

readonly class MembersImport implements ToCollection
{
    public function __construct(
        private MemberService $memberService
    ) {}

    public function collection(Collection $collection): void
    {
        $seenPhones = [];
        $seenCitizenships = [];

        foreach ($collection->skip(1) as $index => $row) {
            $values = $row->values()->toArray();

            if (empty(array_filter(
                $values,
                fn ($value) => $value !== null && $value !== ''
            ))) {
                continue;
            }

            $data = [
                'name' => trim((string) ($values[0] ?? '')),
                'phone' => trim((string) ($values[1] ?? '')),
                'citizenship_number' => trim((string) ($values[2] ?? '')),
                'gender' => strtolower(trim((string) ($values[3] ?? ''))),
                'address' => trim((string) ($values[4] ?? '')),
                'dob' => isset($values[5]) && $values[5] !== ''
                    ? trim((string) $values[5])
                    : null,
                'father_name' => isset($values[6]) && $values[6] !== ''
                    ? trim((string) $values[6])
                    : null,
                'mother_name' => isset($values[7]) && $values[7] !== ''
                    ? trim((string) $values[7])
                    : null,
                'legal_aid_name' => trim((string) ($values[8] ?? '')),
                'legal_aid_relation' => trim((string) ($values[9] ?? '')),
                'legal_aid_citizenship_number' => trim((string) ($values[10] ?? '')),
            ];

            $relation = strtolower($data['legal_aid_relation']);

            $data['legal_aid_type'] = match (true) {
                str_contains($relation, 'father'),
                str_contains($relation, 'pita'),
                str_contains($relation, 'buwa') => 'father',

                str_contains($relation, 'mother'),
                str_contains($relation, 'ama') => 'mother',

                default => 'other',
            };

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20', 'unique:members,phone'],
                'citizenship_number' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:members,citizenship_number',
                ],
                'gender' => ['required', 'in:male,female,other'],
                'address' => ['required', 'string', 'max:500'],
                'father_name' => ['nullable', 'string', 'max:255'],
                'mother_name' => ['nullable', 'string', 'max:255'],
                'legal_aid_type' => ['required', 'in:father,mother,other'],
                'legal_aid_name' => ['required', 'string', 'max:255'],
                'legal_aid_relation' => ['required', 'string', 'max:100'],
                'legal_aid_citizenship_number' => [
                    'required',
                    'string',
                    'max:100',
                ],
            ]);

            $rowNumber = $index + 2;

            if ($validator->fails()) {
                throw new RuntimeException(
                    "Excel row {$rowNumber}: " .
                    $validator->errors()->first()
                );
            }

            if (
                isset($seenPhones[$data['phone']]) ||
                isset($seenCitizenships[$data['citizenship_number']])
            ) {
                throw new RuntimeException(
                    "Excel row {$rowNumber}: Duplicate phone or citizenship number in the Excel file."
                );
            }

            $seenPhones[$data['phone']] = true;
            $seenCitizenships[$data['citizenship_number']] = true;

            $this->memberService->createFromImport(
                ImportMemberDto::fromArray($data)
            );
        }
    }
}
