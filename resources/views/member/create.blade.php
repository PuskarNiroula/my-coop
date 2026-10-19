@extends('layouts.app')

@section('title', 'Add Member')
@section('page-title', 'Add Member')


@section('content')

    @vite(['resources/css/member_create.css'])

    <div class="member-page">
        <div class="page-heading">
            <div>
                <span class="page-eyebrow">Member management</span>
                <h1>Add New Member</h1>
                <p>Register a new member of Jay Durga Krishi Sahakari Sanstha.</p>
            </div>

            <a href="{{ url('/members') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Members
            </a>
        </div>

        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="member-form-layout">
                <div class="member-main-column">
                    <div class="form-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <h2>Personal Information</h2>
                                <p>Enter the member's basic details.</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Full Name <span>*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                                @error('name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number <span>*</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number" required>
                                @error('phone')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <span class="field-label" id="gender-label">Gender <span>*</span></span>
                                <div class="gender-options" role="radiogroup" aria-labelledby="gender-label">
                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }} required>
                                        <span class="gender-card">
                                            <i class="fa-solid fa-mars"></i>
                                            Male
                                        </span>
                                    </label>

                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }} required>
                                        <span class="gender-card">
                                            <i class="fa-solid fa-venus"></i>
                                            Female
                                        </span>
                                    </label>

                                    <label class="gender-option">
                                        <input type="radio" name="gender" value="other" {{ old('gender') === 'other' ? 'checked' : '' }} required>
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
                                <label for="father_name">Father's Name</label>
                                <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="Enter father's name">
                                @error('father_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mother_name">Mother's Name</label>
                                <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="Enter mother's name">
                                @error('mother_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="address">Address <span>*</span></label>
                                <textarea id="address" name="address" rows="3" placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                                @error('address')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="dob_display">Date of Birth (B.S.)</label>
                                <input
                                    type="text"
                                    id="dob_display"
                                    data-nepali-date
                                    data-target="#dob"
                                    placeholder="Select Nepali date"
                                    value="{{ old('dob') }}"
                                    autocomplete="off"
                                    readonly
                                >
                                <input type="hidden" id="dob" name="dob" value="{{ old('dob') }}">
                                @error('dob')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="citizenship_number">Member Citizenship Number <span>*</span></label>
                                <input
                                    type="text"
                                    id="citizenship_number"
                                    name="citizenship_number"
                                    value="{{ old('citizenship_number') }}"
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
                                <p>Select a parent or provide details of another representative.</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="legal_aid_type">Representative Type <span>*</span></label>
                                <select id="legal_aid_type" name="legal_aid_type" required>
                                    <option value="">Select representative</option>
                                    <option value="father" {{ old('legal_aid_type') === 'father' ? 'selected' : '' }}>Father</option>
                                    <option value="mother" {{ old('legal_aid_type') === 'mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="other" {{ old('legal_aid_type') === 'other' ? 'selected' : '' }}>Other Person</option>
                                </select>
                                @error('legal_aid_type')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="legal_aid_name">Representative Name <span>*</span></label>
                                <input
                                    type="text"
                                    id="legal_aid_name"
                                    name="legal_aid_name"
                                    value="{{ old('legal_aid_name') }}"
                                    placeholder="Representative name"
                                    required
                                >
                                @error('legal_aid_name')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" id="legal_aid_relation_group">
                                <label for="legal_aid_relation">
                                    Relationship <span class="relation-required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="legal_aid_relation"
                                    name="legal_aid_relation"
                                    value="{{ old('legal_aid_relation') }}"
                                    placeholder="e.g. Uncle, Aunt, Legal Guardian"
                                    required
                                >
                                @error('legal_aid_relation')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" id="legal_aid_citizenship_group">
                                <label for="legal_aid_citizenship_number">Representative Citizenship Number <span>*</span></label>
                                <input
                                    type="text"
                                    id="legal_aid_citizenship_number"
                                    name="legal_aid_citizenship_number"
                                    value="{{ old('legal_aid_citizenship_number') }}"
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
                                <p>Upload clear images of both sides of the citizenship card.</p>
                            </div>
                        </div>

                        <div class="document-grid">
                            <label class="upload-box" for="citizenship_front">
                                <span class="upload-title">Citizenship Front</span>
                                <input type="file" id="citizenship_front" name="citizenship_front" accept="image/jpeg,image/png,image/webp" class="visually-hidden">
                                <div class="upload-preview" id="front-preview">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Choose front image</span>
                                    <small>JPG, PNG or WEBP</small>
                                </div>
                                @error('citizenship_front')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="upload-box" for="citizenship_back">
                                <span class="upload-title">Citizenship Back</span>
                                <input type="file" id="citizenship_back" name="citizenship_back" accept="image/jpeg,image/png,image/webp" class="visually-hidden">
                                <div class="upload-preview" id="back-preview">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Choose back image</span>
                                    <small>JPG, PNG or WEBP</small>
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
                                <p>Upload a profile photograph.</p>
                            </div>
                        </div>

                        <label for="image" class="profile-upload">
                            <div class="profile-preview" id="profile-preview">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span class="upload-action">
                                <i class="fa-solid fa-upload"></i>
                                Choose Photo
                            </span>
                            <small>JPG, PNG or WEBP · Max 2 MB</small>
                        </label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="visually-hidden">
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
                            <li>Use a clear, recent member photograph.</li>
                            <li>Ensure citizenship details are readable.</li>
                            <li>Accepted formats: JPG, PNG and WEBP.</li>
                            <li>Keep each image under 2 MB.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ url('/members') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i>
                    Save Member
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewImage = (inputId, previewId, mode = 'document') => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                if (!input || !preview) return;

                input.addEventListener('change', function () {
                    const file = this.files[0];

                    if (!file) return;

                    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                        this.value = '';
                        alert('Please choose a JPG, PNG or WEBP image.');
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        this.value = '';
                        alert('Please choose an image smaller than 2 MB.');
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (event) {
                        if (mode === 'profile') {
                            preview.innerHTML = `<img src="${event.target.result}" alt="Member photo preview">`;
                        } else {
                            preview.innerHTML = `<img src="${event.target.result}" alt="Citizenship document preview"><span class="preview-change">Change image</span>`;
                        }
                    };

                    reader.readAsDataURL(file);
                });
            };

            previewImage('image', 'profile-preview', 'profile');
            previewImage('citizenship_front', 'front-preview');
            previewImage('citizenship_back', 'back-preview');

            const type = document.getElementById('legal_aid_type');
            const name = document.getElementById('legal_aid_name');
            const relation = document.getElementById('legal_aid_relation');
            const relationGroup = document.getElementById('legal_aid_relation_group');
            const relationRequired = relationGroup.querySelector('.relation-required');
            const citizenship = document.getElementById('legal_aid_citizenship_number');
            const citizenshipGroup = document.getElementById('legal_aid_citizenship_group');
            const father = document.getElementById('father_name');
            const mother = document.getElementById('mother_name');

            let previousType = type.value;

            function updateRepresentative() {
                const selected = type.value;

                if (selected === 'father' || selected === 'mother') {
                    name.value = selected === 'father' ? father.value : mother.value;
                    name.readOnly = true;

                    relation.value = selected === 'father' ? 'Father' : 'Mother';
                    relation.readOnly = true;
                    relation.required = true;
                    relationRequired.style.display = 'inline';

                    citizenship.required = true;
                    citizenshipGroup.style.display = '';
                } else if (selected === 'other') {
                    if (previousType !== 'other') {
                        name.value = '';
                        relation.value = '';
                        citizenship.value = '';
                    }

                    name.readOnly = false;
                    relation.readOnly = false;
                    relation.required = true;
                    relationRequired.style.display = 'inline';

                    citizenship.required = true;
                    citizenshipGroup.style.display = '';
                } else {
                    name.value = '';
                    name.readOnly = false;

                    relation.value = '';
                    relation.readOnly = false;
                    relation.required = false;
                    relationRequired.style.display = 'none';

                    citizenship.value = '';
                    citizenship.required = false;
                    citizenshipGroup.style.display = 'none';
                }

                previousType = selected;
            }

            type.addEventListener('change', updateRepresentative);

            father.addEventListener('input', function () {
                if (type.value === 'father') {
                    name.value = father.value;
                }
            });

            mother.addEventListener('input', function () {
                if (type.value === 'mother') {
                    name.value = mother.value;
                }
            });

            updateRepresentative();
        });
    </script>
@endsection
