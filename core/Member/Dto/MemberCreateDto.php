<?php

namespace Member\Dto;

use Illuminate\Http\UploadedFile;

class MemberCreateDto
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $father_name,
        public ?string $mother_name,
        public string $address,
        public string $gender,
        public ?string $dob,
        public string $citizenship_number,
        public string $legal_aid_type,
        public string $legal_aid_name,
        public string $legal_aid_relation,
        public string $legal_aid_citizenship_number,
        public ?UploadedFile $citizenship_front,
        public ?UploadedFile $citizenship_back,
        public ?UploadedFile $image,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            father_name: $data['father_name'] ?? null,
            mother_name: $data['mother_name'] ?? null,
            address: $data['address'],
            dob: $data['dob'] ?? null,
            gender: $data['gender'],
            citizenship_number: $data['citizenship_number'],
            legal_aid_type: $data['legal_aid_type'],
            legal_aid_name: $data['legal_aid_name'],
            legal_aid_relation: $data['legal_aid_relation'],
            legal_aid_citizenship_number: $data['legal_aid_citizenship_number'],
            citizenship_front: $data['citizenship_front'] ?? null,
            citizenship_back: $data['citizenship_back'] ?? null,
            image: $data['image'] ?? null,
        );
    }
}
