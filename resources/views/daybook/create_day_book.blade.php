@extends('layouts.app')

@section('content')
    <div class="daybook-create-page">
        <div class="daybook-create-header">
            <div>
                <h1 class="daybook-create-title">Create Day Book</h1>
                <p class="daybook-create-subtitle">
                    Create a day book for the selected Nepali date.
                </p>
            </div>

            <a href="{{ route('daybook.index') }}" class="daybook-back-btn">
                <span aria-hidden="true">&larr;</span>
                Back to Day Books
            </a>
        </div>

        <div class="daybook-create-card">
            <div class="daybook-card-header">
                <div class="daybook-card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h2>Day Book Details</h2>
                    <p>Enter the date to create a new day book.</p>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('daybook.store') }}"
                class="daybook-create-form"
                id="daybookCreateForm"
            >
                @csrf

                <div class="daybook-form-group">
                    <label for="dateNepali">
                        Nepali Date <span class="daybook-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="dateNepali"
                        name="dateNepali"
                        value="{{ old('dateNepali') }}"
                        data-nepali-date
                        placeholder="YYYY-MM-DD"
                        autocomplete="off"
                        required
                        class="@error('dateNepali') daybook-input-error @enderror"
                    >

                    @error('dateNepali')
                    <span class="daybook-error-message">{{ $message }}</span>
                    @enderror

                    <small class="daybook-field-hint">
                        Enter the date in YYYY-MM-DD format.
                    </small>
                </div>

                <div class="daybook-form-group">
                    <label for="lastTransactionDateNepali">
                        Last Transaction Date
                    </label>

                    <input
                        type="text"
                        id="lastTransactionDateNepali"
                        value="{{ $lastTransactionDateNepali ?: 'No previous transactions' }}"
                        readonly
                        class="daybook-readonly-input"
                    >

                    <small class="daybook-field-hint">
                        A date earlier than the last transaction date is not allowed.
                    </small>
                </div>

                <div class="daybook-form-footer">
                    <a href="{{ route('daybook.index') }}" class="daybook-cancel-btn">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="daybook-save-btn btn-primary"
                        id="daybookSubmitBtn"
                    >
                        <i class="fas fa-save"></i>
                        Create Day Book
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/day_book_create.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('daybookCreateForm');
            const submitButton = document.getElementById('daybookSubmitBtn');
            const nepaliDateInput = document.getElementById('dateNepali');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const nepaliDate = nepaliDateInput.value.trim();

                Swal.fire({
                    title: 'Create Day Book?',
                    text: `Are you sure you want to create a day book for ${nepaliDate}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, create it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
