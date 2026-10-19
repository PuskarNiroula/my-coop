@extends('layouts.app')

@section('title', 'New Saving Withdrawal')
@section('page-title', 'New Saving Withdrawal')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-1">New Withdrawal</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Withdrawal Information</h6>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('savings.withdrawal.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="member_id" class="form-label fw-semibold">Member <span class="text-danger">*</span></label>
                            <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                <option value="">Select Member</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->member_id }}" {{ old('member_id', request('member_id')) == $member->member_id ? 'selected' : '' }}>
                                        {{ $member->name }} - {{ $member->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="balance" class="form-label fw-semibold">Balance </label>
                            <div class="input-group">
                                <span class="input-group-text">Populate with selected member's balance</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label fw-semibold">Withdrawal Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0.01" step="0.01" placeholder="Enter withdrawal amount" required>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method" class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="reference_number" class="form-label fw-semibold">Reference Number</label>
                            <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}" placeholder="Optional transaction or cheque number">
                            @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="remarks" class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
                            <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3" placeholder="Enter any additional details" required>{{ old('remarks') }}</textarea>
                            @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url()->previous() }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Save Withdrawal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/create_deposit.css')
@endpush
