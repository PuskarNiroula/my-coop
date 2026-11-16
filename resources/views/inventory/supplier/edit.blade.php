@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Supplier</h1>
                <p>Update supplier details.</p>
            </div>

            <a href="{{ route('suppliers.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Suppliers</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('suppliers.update', $supplier->getId()) }}" method="POST" id="supplierForm">
                @csrf
                @method('PUT')

                <div class="members-toolbar">
                    <h3>Supplier Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Supplier Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $supplier->name) }}"
                            placeholder="e.g. ABC Suppliers"
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
                            value="{{ old('phone', $supplier->phone) }}"
                            placeholder="e.g. 9800000000"
                            maxlength="20"
                            required
                        >

                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Address <span>*</span></label>

                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            rows="4"
                            placeholder="Enter supplier address"
                            maxlength="1000"
                            required
                        >{{ old('address', $supplier->address) }}</textarea>

                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('suppliers.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Update Supplier</span>
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
        document.getElementById('supplierForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this supplier?',
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
