<?php

namespace Member\Service;

use Member\Dto\ImportMemberDto;
use Member\Enum\GenderEnum;
use Member\Dto\MemberCreateDto;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Member\Exception\GenderNotAllowedException;
use Throwable;

class MemberService
{
    /**
     * @throws Throwable
     * @throws GenderNotAllowedException
     */
    public function create(MemberCreateDto $dto): Member
    {
        $storedFiles = [];

        if(!in_array($dto->gender, GenderEnum::values()))
            throw new GenderNotAllowedException();

        try {

            $citizenshipFront = $dto->citizenship_front
                ? $dto->citizenship_front->store('members/citizenship', 'public')
                : null;

            if ($citizenshipFront) {
                $storedFiles[] = $citizenshipFront;
            }

            $citizenshipBack = $dto->citizenship_back
                ? $dto->citizenship_back->store('members/citizenship', 'public')
                : null;

            if ($citizenshipBack) {
                $storedFiles[] = $citizenshipBack;
            }

            $image = $dto->image
                ? $dto->image->store('members/photos', 'public')
                : null;

            if ($image) {
                $storedFiles[] = $image;
            }

            return DB::transaction(function () use (
                $dto,
                $citizenshipFront,
                $citizenshipBack,
                $image
            ) {
                return Member::create([
                    'name' => $dto->name,
                    'phone' => $dto->phone,
                    'father_name' => $dto->father_name,
                    'mother_name' => $dto->mother_name,
                    'address' => $dto->address,
                    'dob' => $dto->dob,
                    'gender' => $dto->gender,
                    'citizenship_number' => $dto->citizenship_number,
                    'legal_aid_type' => $dto->legal_aid_type,
                    'legal_aid_name' => $dto->legal_aid_name,
                    'legal_aid_relation' => $dto->legal_aid_relation,
                    'legal_aid_citizenship_number' => $dto->legal_aid_citizenship_number,
                    'citizenship_front' => $citizenshipFront,
                    'citizenship_back' => $citizenshipBack,
                    'image' => $image,
                ]);
            });
        } catch (Throwable $exception) {
            foreach ($storedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $exception;
        }
    }


        public function createFromImport(ImportMemberDto $dto): Member
        {
            return DB::transaction(function () use ($dto) {
                return Member::create([
                    'name' => $dto->name,
                    'gender' => $dto->gender,
                    'phone' => $dto->phone,
                    'father_name' => $dto->father_name,
                    'mother_name' => $dto->mother_name,
                    'address' => $dto->address,
                    'dob' => $dto->dob,
                    'citizenship_number' => $dto->citizenship_number,
                    'legal_aid_type' => $dto->legal_aid_type,
                    'legal_aid_name' => $dto->legal_aid_name,
                    'legal_aid_relation' => $dto->legal_aid_relation,
                    'legal_aid_citizenship_number' => $dto->legal_aid_citizenship_number,
                ]);
            });

        }
}
