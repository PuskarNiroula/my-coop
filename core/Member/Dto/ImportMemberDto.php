<?php

namespace Member\Dto;

class ImportMemberDto
{
    public function __construct(
        public string $name,
        public string $gender,
        public string $phone,
        public ?string $father_name,
        public ?string $mother_name,
        public string $address,
        public ?string $dob,
        public string $citizenship_number,
        public string $legal_aid_type,
        public string $legal_aid_name,
        public string $legal_aid_relation,
        public string $legal_aid_citizenship_number,
        public string $account_type,
        public float $opening_balance,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            gender: (string) $data['gender'],
            phone: (string) $data['phone'],
            father_name: isset($data['father_name']) && $data['father_name'] !== ''
                ? (string) $data['father_name']
                : null,
            mother_name: isset($data['mother_name']) && $data['mother_name'] !== ''
                ? (string) $data['mother_name']
                : null,
            address: (string) $data['address'],
            dob: isset($data['dob']) && $data['dob'] !== ''
                ? (string) $data['dob']
                : null,
            citizenship_number: (string) $data['citizenship_number'],
            legal_aid_type: (string) $data['legal_aid_type'],
            legal_aid_name: (string) $data['legal_aid_name'],
            legal_aid_relation: (string) $data['legal_aid_relation'],
            legal_aid_citizenship_number: (string) $data['legal_aid_citizenship_number'],
            account_type: (string) $data['account_type'],
            opening_balance: (float) $data['opening_balance'],
        );
    }
}

