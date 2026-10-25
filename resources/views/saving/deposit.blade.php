@extends('layouts.app')

@section('title', 'New Saving Deposit')
@section('page-title', 'New Saving Deposit')

@section('content')
    <div class="container-fluid px-4">
        <div class="member-page">
            <div class="page-heading">
                <div>
                    <span class="page-eyebrow">SAVINGS MANAGEMENT</span>
                    <h1>New Deposit</h1>
                    <p>Record a savings deposit for a registered member.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="flash-message flash-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        <strong>Please correct the following errors.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="flash-message flash-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="member-form-layout">
                <div class="member-main-column">
                    <div class="form-card">
                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="bi bi-piggy-bank"></i>
                            </div>
                            <div>
                                <h2>Deposit Information</h2>
                                <p>Enter the member and deposit details below.</p>
                            </div>
                        </div>

                        <form action="{{ route('savings.deposit.store') }}" method="POST">
                            @csrf

                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label for="member_id">
                                        Member <span>*</span>
                                    </label>
                                    <select
                                        name="member_id"
                                        id="member_id"
                                        class="@error('member_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Select Member</option>
                                        @foreach ($members as $member)
                                            <option
                                                value="{{ $member->member_id }}"
                                                {{ old('member_id', request('member_id')) == $member->member_id ? 'selected' : '' }}
                                            >
                                                {{ $member->name }} - {{ $member->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group full-width">
                                    <label for="amount">
                                        Deposit Amount <span>*</span>
                                    </label>
                                    <div class="deposit-amount-wrapper">
                                        <span class="deposit-currency">Rs.</span>
                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            class="@error('amount') is-invalid @enderror"
                                            value="{{ old('amount') }}"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="Enter deposit amount"
                                            required
                                        >
                                    </div>
                                    @error('amount')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group full-width">
                                    <label for="remarks">Remarks</label>
                                    <textarea
                                        name="remarks"
                                        id="remarks"
                                        rows="4"
                                        placeholder="Enter any additional details"
                                    >{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ url()->previous() }}" class="btn-secondary">
                                    <i class="bi bi-arrow-left"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Save Deposit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="member-side-column">
                    <div class="form-card tips-card">
                        <div class="tips-title">
                            <i class="bi bi-lightbulb"></i>
                            Deposit Guidelines
                        </div>
                        <ul>
                            <li>Select the correct registered member before recording a deposit.</li>
                            <li>Enter an amount greater than zero.</li>
                            <li>Verify the amount before saving the transaction.</li>
                            <li>Use remarks to record any additional deposit details.</li>
                        </ul>
                    </div>

                    <div class="form-card deposit-note-card">
                        <div class="deposit-note-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Transaction Details</h3>
                        <p>
                            Ensure the deposit information is accurate before submitting
                            the transaction.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/create_deposit.css')
@endpush
@push('scripts')
    <script>
        document.getElementById('amount').addEventListener('wheel', function (event) {
            event.preventDefault();
        }, { passive: false });
    </script>
@endpush
