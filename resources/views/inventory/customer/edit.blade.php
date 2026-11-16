@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Customer</h1>
                <p>Update customer information.</p>
            </div>

            <a href="{{ route('customers.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Customers</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('customers.update', $customer->getId()) }}" method="POST" id="customerForm">
                @csrf
                @method('PUT')

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
                            value="{{ old('name', $customer->name) }}"
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
                            value="{{ old('phone', $customer->phone) }}"
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
                        >{{ old('address', $customer->address) }}</textarea>

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
                        <span>Update Customer</span>
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
                text: 'Do you want to update this customer?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
