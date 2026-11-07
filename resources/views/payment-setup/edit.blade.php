@extends('layouts.app')

@section('title', 'Edit Payment Method')
@section('page-title', 'Edit Payment Method')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Payment Method</h1>
                <p>Update payment method details.</p>
            </div>

            <a href="{{ route('payment-setup.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Payment Methods</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('payment-setup.update', $paymentMethod->getId()) }}" method="POST" id="paymentMethodForm">
                @csrf
                @method('PUT')

                <div class="members-toolbar">
                    <h3>Payment Method Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Payment Method Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $paymentMethod->name) }}"
                            placeholder="e.g. Cash, Bank, eSewa"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('payment-setup.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Update Payment Method</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('paymentMethodForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this payment method?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
