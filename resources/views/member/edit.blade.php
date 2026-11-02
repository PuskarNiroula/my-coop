
@extends('layouts.app')

@section('title', 'Edit Member')
@section('page-title', 'Edit Member')

@section('content')

    @vite(['resources/css/member_create.css'])

    <div class="member-page">
        <div class="page-heading">
            <div>
                <span class="page-eyebrow">Member management</span>
                <h1>Edit Member</h1>
                <p>Update the details of {{ $member->name }}.</p>
            </div>

            <a href="{{ url('/members') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Members
            </a>
        </div>

        <form id="memberForm" action="{{ route('member.update', $member->getId()) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="member-form-layout">
                <div class="member-main-column">

                    <div class="form-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <h2>Personal Information</h2>
                                <p>Update the member's basic details.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label for="name">Full Name <span>*</span></label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $member->name) }}"
                                       placeholder="Enter full name" required>
                                @error('name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number <span>*</span></label>
                                <input type="tel" id="phone" name="phone"
                                       value="{{ old('phone', $member->phone) }}"
                                       placeholder="Enter phone number" required>
                                @error('phone')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <span class="field-label" id="gender-label">Gender <span>*</span></span>

                                <div class="gender-options" role="radiogroup" aria-labelledby="gender-label">
                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="male"
                                               {{ old('gender', $member->gender) === 'male' ? 'checked' : '' }} required>
                                        <span class="gender-card">
                                            <i class="fa-solid fa-mars"></i>
                                            Male
                                        </span>
                                    </label>

                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="female"
                                               {{ old('gender', $member->gender) === 'female' ? 'checked' : '' }} required>
                                        <span class="gender-card">
                                            <i class="fa-solid fa-venus"></i>
                                            Female
                                        </span>
                                    </label>

                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="other"
                                               {{ old('gender', $member->gender) === 'other' ? 'checked' : '' }} required>
                                        <span class="gender-card">
                                            <i class="fa-solid fa-genderless"></i>
                                            Other
                                        </span>
                                    </label>
                                </div>

                                @error('gender')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="father_name">Father's Name <span>*</span></label>
                                <input type="text" id="father_name" name="father_name"
                                       value="{{ old('father_name', $member->father_name) }}"
                                       placeholder="Enter father's name" required>
                                @error('father_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mother_name">Mother's Name <span>*</span></label>
                                <input type="text" id="mother_name" name="mother_name"
                                       value="{{ old('mother_name', $member->mother_name) }}"
                                       placeholder="Enter mother's name" required>
                                @error('mother_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="address">Address <span>*</span></label>
                                <textarea id="address" name="address" rows="3"
                                          placeholder="Enter complete address" required>{{ old('address', $member->address) }}</textarea>
                                @error('address')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="dob_nepali">Date of Birth (Nepali) <span>*</span></label>
                                <input
                                    type="text"
                                    id="dob_nepali"
                                    name="dob_nepali"
                                    data-nepali-date
                                    placeholder="Select Nepali date"
                                    value="{{ old('dob_nepali', $member->getNepaliBirthDate()) }}"
                                    required
                                >

                                @error('dob_nepali')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="citizenship_number">Citizenship Number <span>*</span></label>
                                <input
                                    type="text"
                                    id="citizenship_number"
                                    name="citizenship_number"
                                    value="{{ old('citizenship_number', $member->citizenship_number) }}"
                                    placeholder="Enter citizenship number"
                                    required
                                >
                                @error('citizenship_number')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h2>Legal Representative / Guardian</h2>
                                <p>Update the member's legal representative details.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label for="legal_aid_type">Representative Type <span>*</span></label>
                                <select id="legal_aid_type" name="legal_aid_type" required>
                                    <option value="">Select representative</option>
                                    <option value="father" {{ old('legal_aid_type', $member->legal_aid_relation) === 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="mother" {{ old('legal_aid_type', $member->legal_aid_relation) === 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="other" {{ old('legal_aid_type', $member->legal_aid_relation) === 'other' ? 'selected' : '' }}>Other Person</option>
                                </select>
                                @error('legal_aid_type')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="legal_aid_name">Legal Representative's Name (Hakwala) <span>*</span></label>
                                <input
                                    type="text"
                                    id="legal_aid_name"
                                    name="legal_aid_name"
                                    value="{{ old('legal_aid_name', $member->legal_aid_name) }}"
                                    placeholder="Enter representative name"
                                    required
                                >
                                @error('legal_aid_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" id="legal_aid_relation_group">
                                <label for="legal_aid_relation">
                                    Legal Representative's Relation <span class="relation-required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="legal_aid_relation"
                                    name="legal_aid_relation"
                                    value="{{ old('legal_aid_relation', $member->legal_aid_relation) }}"
                                    placeholder="e.g. Father, Mother, Uncle, Guardian"
                                >
                                @error('legal_aid_relation')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" id="legal_aid_citizenship_group">
                                <label for="legal_aid_citizenship_number">Legal Representative's Citizenship Number <span>*</span></label>
                                <input
                                    type="text"
                                    id="legal_aid_citizenship_number"
                                    name="legal_aid_citizenship_number"
                                    value="{{ old('legal_aid_citizenship_number', $member->legal_aid_citizenship_number) }}"
                                    placeholder="Enter representative's citizenship number"
                                >
                                @error('legal_aid_citizenship_number')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="form-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <div>
                                <h2>Citizenship Documents</h2>
                                <p>Upload new images to replace the existing citizenship documents.</p>
                            </div>
                        </div>

                        <div class="document-grid">

                            <label class="upload-box" for="citizenship_front">
                                <span class="upload-title">Citizenship Front</span>
                                <input
                                    type="file"
                                    id="citizenship_front"
                                    name="citizenship_front"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="visually-hidden"
                                >
                                <div class="upload-preview" id="front-preview">
                                    @if($member->citizenship_front)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($member->citizenship_front) }}" alt="Citizenship front">
                                        <span class="preview-change">Change image</span>
                                    @else
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Choose front image</span>
                                        <small>JPG, PNG or WEBP</small>
                                    @endif
                                </div>
                                @error('citizenship_front')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="upload-box" for="citizenship_back">
                                <span class="upload-title">Citizenship Back</span>
                                <input
                                    type="file"
                                    id="citizenship_back"
                                    name="citizenship_back"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="visually-hidden"
                                >
                                <div class="upload-preview" id="back-preview">
                                    @if($member->citizenship_back)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($member->citizenship_back) }}" alt="Citizenship back">
                                        <span class="preview-change">Change image</span>
                                    @else
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Choose back image</span>
                                        <small>JPG, PNG or WEBP</small>
                                    @endif
                                </div>
                                @error('citizenship_back')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </label>

                        </div>
                    </div>

                </div>

                <div class="member-side-column">

                    <div class="form-card profile-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <div>
                                <h2>Member Photo</h2>
                                <p>Update the profile photograph.</p>
                            </div>
                        </div>

                        <label for="image" class="profile-upload">
                            <div class="profile-preview" id="profile-preview">
                                @if($member->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($member->image) }}" alt="Member photo">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <span class="upload-action">
                                <i class="fa-solid fa-upload"></i>
                                Choose Photo
                            </span>
                            <small>JPG, PNG or WEBP · Max 2 MB</small>
                        </label>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/jpeg,image/png,image/webp"
                            class="visually-hidden"
                        >

                        @error('image')
                        <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-card tips-card">
                        <div class="tips-title">
                            <i class="fa-solid fa-circle-info"></i>
                            Upload Guidelines
                        </div>
                        <ul>
                            <li>Upload a clear, recent member photograph.</li>
                            <li>Ensure citizenship details are readable.</li>
                            <li>Accepted formats: JPG, PNG and WEBP.</li>
                            <li>Keep each image under 2 MB.</li>
                            <li>Image uploads are optional.</li>
                            <li>Leave an image unchanged to keep the existing file.</li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="form-actions">
                <a href="{{ url('/members') }}" class="btn-secondary">Cancel</a>

                <button type="submit" class="btn-primary" id="submitMember">
                    <i class="fa-solid fa-check"></i>
                    Update Member
                </button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('memberForm');
            const submitButton = document.getElementById('submitMember');

            const dobNepali = document.getElementById('dob_nepali');

            const type = document.getElementById('legal_aid_type');
            const representativeName = document.getElementById('legal_aid_name');
            const relation = document.getElementById('legal_aid_relation');
            const relationGroup = document.getElementById('legal_aid_relation_group');
            const relationRequired = relationGroup.querySelector('.relation-required');

            const citizenship = document.getElementById('legal_aid_citizenship_number');
            const citizenshipGroup = document.getElementById('legal_aid_citizenship_group');

            const father = document.getElementById('father_name');
            const mother = document.getElementById('mother_name');

            let isSubmitting = false;

            const imageInputs = [
                {
                    input: 'image',
                    preview: 'profile-preview',
                    mode: 'profile'
                },
                {
                    input: 'citizenship_front',
                    preview: 'front-preview',
                    mode: 'document'
                },
                {
                    input: 'citizenship_back',
                    preview: 'back-preview',
                    mode: 'document'
                }
            ];

            function previewImage(inputId, previewId, mode) {

                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                if (!input || !preview) return;

                input.addEventListener('change', function () {

                    const file = this.files[0];

                    if (!file) return;

                    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {

                        this.value = '';

                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Image',
                            text: 'Please select a JPG, PNG or WEBP image.'
                        });

                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {

                        this.value = '';

                        Swal.fire({
                            icon: 'error',
                            title: 'Image Too Large',
                            text: 'Image size must not exceed 2 MB.'
                        });

                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (event) {

                        if (mode === 'profile') {

                            preview.innerHTML = `
                                <img src="${event.target.result}" alt="Member photo preview">
                            `;

                        } else {

                            preview.innerHTML = `
                                <img src="${event.target.result}" alt="Document preview">
                                <span class="preview-change">Change image</span>
                            `;

                        }

                    };

                    reader.readAsDataURL(file);

                });

            }

            imageInputs.forEach(item => {
                previewImage(item.input, item.preview, item.mode);
            });

            function updateRepresentative() {

                const selected = type.value;

                if (selected === 'father') {

                    representativeName.value = father.value;
                    representativeName.readOnly = true;

                    relation.value = 'Father';
                    relation.readOnly = true;
                    relation.required = true;
                    relationRequired.style.display = 'inline';

                    citizenship.required = true;
                    citizenshipGroup.style.display = '';

                } else if (selected === 'mother') {

                    representativeName.value = mother.value;
                    representativeName.readOnly = true;

                    relation.value = 'Mother';
                    relation.readOnly = true;
                    relation.required = true;
                    relationRequired.style.display = 'inline';

                    citizenship.required = true;
                    citizenshipGroup.style.display = '';

                } else if (selected === 'other') {

                    representativeName.readOnly = false;
                    representativeName.required = true;

                    relation.readOnly = false;
                    relation.required = true;
                    relationRequired.style.display = 'inline';

                    citizenship.required = true;
                    citizenshipGroup.style.display = '';

                } else {

                    representativeName.value = '';
                    representativeName.readOnly = false;
                    representativeName.required = true;

                    relation.value = '';
                    relation.readOnly = false;
                    relation.required = false;
                    relationRequired.style.display = 'none';

                    citizenship.value = '';
                    citizenship.required = false;
                    citizenshipGroup.style.display = 'none';

                }

            }

            type.addEventListener('change', updateRepresentative);

            father.addEventListener('input', function () {

                if (type.value === 'father') {
                    representativeName.value = father.value;
                }

            });

            mother.addEventListener('input', function () {

                if (type.value === 'mother') {
                    representativeName.value = mother.value;
                }

            });

            updateRepresentative();

            const customMessages = {
                name: 'Please enter the member name.',
                phone: 'Please enter the phone number.',
                gender: 'Please select the member gender.',
                father_name: "Please enter the father's name.",
                mother_name: "Please enter the mother's name.",
                address: 'Please enter the complete address.',
                citizenship_number: 'Please enter the citizenship number.',
                legal_aid_type: 'Please select the representative type.',
                legal_aid_name: 'Please enter the representative name.',
                legal_aid_relation: 'Please enter the representative relationship.',
                legal_aid_citizenship_number: "Please enter the representative's citizenship number."
            };

            function validateForm() {

                updateRepresentative();

                const invalidFields = [];

                const fields = form.querySelectorAll(
                    'input[required], select[required], textarea[required]'
                );

                fields.forEach(field => {

                    if (field.type === 'hidden') return;

                    if (!field.checkValidity()) {

                        const message = customMessages[field.name] || field.validationMessage;

                        invalidFields.push({
                            field: field,
                            message: message
                        });

                    }

                });

                if (!dobNepali.value.trim()) {

                    invalidFields.push({
                        field: dobNepali,
                        message: 'Please select the Nepali date of birth.'
                    });

                }

                if (invalidFields.length > 0) {

                    const firstInvalid = invalidFields[0];

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: firstInvalid.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    }).then(() => {

                        firstInvalid.field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        firstInvalid.field.focus();

                    });

                    return false;

                }

                return true;

            }

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                if (isSubmitting) return;

                if (!validateForm()) return;

                Swal.fire({
                    title: 'Confirm Member Update',
                    text: 'Are you sure you want to update this member?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update Member',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true
                }).then(result => {

                    if (result.isConfirmed) {

                        isSubmitting = true;

                        submitButton.disabled = true;

                        submitButton.innerHTML = `
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            Updating...
                        `;

                        form.submit();

                    }

                });

            });

        });
    </script>

@endsection
