<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'phone' => ['required', 'string', 'max:20', 'unique:members,phone'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'dob' => ['nullable', 'date'],
            'citizenship_number' => ['required', 'string', 'max:100', 'unique:members,citizenship_number'],
            'legal_aid_type' => ['required', 'in:father,mother,other'],
            'legal_aid_name' => ['required', 'string', 'max:255'],
            'legal_aid_relation' => ['required', 'string', 'max:100'],
            'legal_aid_citizenship_number' => ['required', 'string', 'max:100'],
            'citizenship_front' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'citizenship_back' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

}
