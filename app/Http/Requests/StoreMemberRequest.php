<?php

namespace App\Http\Requests;

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

            'father_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],

            'address' => ['required', 'string', 'max:500'],
            'dob_nepali' => ['required', 'string', 'max:20'],

            'citizenship_number' => [
                'required',
                'string',
                'max:100',
                'unique:members,citizenship_number'
            ],

            'account_type' => ['required', 'integer'],
            'opening_balance' => ['required', 'numeric', 'min:0'],

            'legal_aid_type' => ['required', 'in:father,mother,other'],
            'legal_aid_name' => ['required', 'string', 'max:255'],
            'legal_aid_relation' => [
                'required_if:legal_aid_type,other',
                'nullable',
                'string',
                'max:100'
            ],
            'legal_aid_citizenship_number' => [
                'required',
                'string',
                'max:100'
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'citizenship_front' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'citizenship_back' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the member name.',
            'name.string' => 'Member name must be valid text.',
            'name.max' => 'Member name cannot exceed 255 characters.',

            'gender.required' => 'Please select the member gender.',
            'gender.in' => 'Please select a valid gender.',

            'phone.required' => 'Please enter the member phone number.',
            'phone.string' => 'Phone number must be valid text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.unique' => 'This phone number is already registered.',

            'father_name.required' => "Please enter the father's name.",
            'father_name.string' => "Father's name must be valid text.",
            'father_name.max' => "Father's name cannot exceed 255 characters.",

            'mother_name.required' => "Please enter the mother's name.",
            'mother_name.string' => "Mother's name must be valid text.",
            'mother_name.max' => "Mother's name cannot exceed 255 characters.",

            'address.required' => 'Please enter the member address.',
            'address.string' => 'Address must be valid text.',
            'address.max' => 'Address cannot exceed 500 characters.',

            'date_of_brith_nepali.required' => 'Please select the member date of birth.',
            'date_of_brith_nepali.string' => 'Date of birth must be valid text.',
            'date_of_brith_nepali.max' => 'Date of birth cannot exceed 20 characters.',

            'citizenship_number.required' => 'Please enter the member citizenship number.',
            'citizenship_number.string' => 'Citizenship number must be valid text.',
            'citizenship_number.max' => 'Citizenship number cannot exceed 100 characters.',
            'citizenship_number.unique' => 'This citizenship number is already registered.',

            'account_type.required' => 'Please select the account type.',
            'account_type.integer' => 'Account type must be a valid integer.',

            'opening_balance.required' => 'Please enter the opening balance.',
            'opening_balance.numeric' => 'Opening balance must be a valid number.',
            'opening_balance.min' => 'Opening balance cannot be negative.',

            'legal_aid_type.required' => 'Please select the legal representative type.',
            'legal_aid_type.in' => 'Please select a valid legal representative type.',

            'legal_aid_name.required' => 'Please enter the legal representative name.',
            'legal_aid_name.string' => 'Legal representative name must be valid text.',
            'legal_aid_name.max' => 'Legal representative name cannot exceed 255 characters.',

            'legal_aid_relation.required_if' => 'Please enter the relationship with the member.',
            'legal_aid_relation.string' => 'Relationship must be valid text.',
            'legal_aid_relation.max' => 'Relationship cannot exceed 100 characters.',

            'legal_aid_citizenship_number.required' => "Please enter the legal representative's citizenship number.",
            'legal_aid_citizenship_number.string' => "Legal representative's citizenship number must be valid text.",
            'legal_aid_citizenship_number.max' => "Legal representative's citizenship number cannot exceed 100 characters.",

            'image.image' => 'Member photo must be a valid image.',
            'image.mimes' => 'Member photo must be JPG, JPEG, PNG or WEBP.',
            'image.max' => 'Member photo cannot exceed 2 MB.',

            'citizenship_front.image' => 'Citizenship front must be a valid image.',
            'citizenship_front.mimes' => 'Citizenship front must be JPG, JPEG, PNG or WEBP.',
            'citizenship_front.max' => 'Citizenship front image cannot exceed 2 MB.',

            'citizenship_back.image' => 'Citizenship back must be a valid image.',
            'citizenship_back.mimes' => 'Citizenship back must be JPG, JPEG, PNG or WEBP.',
            'citizenship_back.max' => 'Citizenship back image cannot exceed 2 MB.',
        ];
    }
}
