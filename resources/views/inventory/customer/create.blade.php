@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Add Customer</h1>
                <p>Create a new customer.</p>
            </div>

            <a href="{{ route('customers.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Customers</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('customers.store') }}" method="POST" id="customerForm">
                @csrf

                <div class="members-toolbar">
                    <h3>Customer Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">

                    <div class="form-group">
                        <label for="name">Customer Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Enter customer name"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span>*</span></label>

                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            class="form-control"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone number"
                            maxlength="20"
                            required
                        >

                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>

                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            rows="4"
                            placeholder="Enter customer address"
                            maxlength="500"
                        >{{ old('address') }}</textarea>

                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="members-toolbar">
                    <a href="{{ route('customers.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Save Customer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush

@push('scripts')
    <script>
        document.getElementById('customerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const name = document.getElementById('name').value.trim();
            const phone = document.getElementById('phone').value.trim();

            if (!name || !phone) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    text: 'Please enter customer name and phone number.'
                });

                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to create this customer?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
