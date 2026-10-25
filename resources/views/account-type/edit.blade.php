@extends('layouts.app')

@section('title', 'Edit Account Type')
@section('page-title', 'Edit Account Type')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Create Account Type.</h1>
            <p>Configure an account type, interest rate and calculation frequency.</p>
        </div>

        <a href="{{ route('account-types.index') }}" class="btn-secondary">
            Back to Account Types
        </a>
    </section>

    <section class="dashboard-content">

        @if ($errors->any())
            <div class="field-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('account-types.update',$accountType->getId()) }}" method="POST">
            @csrf

            <section class="form-card">
                <div class="form-card-heading">
                    <span class="section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18M16 14h2"/>
                        </svg>
                    </span>
                    <div>
                        <h3>Account Type Details</h3>
                        <p>Provide the basic account and interest settings.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Account Type Name <span>*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{$accountType->name}}"
                            placeholder="e.g. Regular Savings"
                            required
                            maxlength="255"
                        >
                        @error('name')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="interest_rate">Interest Rate (%) <span>*</span></label>
                        <input
                            type="number"
                            id="interest_rate"
                            name="interest_rate"
                            value="{{ $accountType->interest_rate }}"
                            placeholder="e.g. 5.00"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                        >
                        @error('interest_rate')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="interest_calculation_frequency">Interest Calculation Frequency <span>*</span></label>
                        <select
                            id="interest_calculation_frequency"
                            name="interest_calculation_frequency"
                            required
                        >

                            @foreach($interestFrequencyArray as $key=>$value)
                                <option value="{{$value}}"
                                @if($value ==$accountType->interest_payout_frequency)
                                    selected
                                    @endif
                                >{{$key}}</option>
                            @endforeach
                        </select>
                        @error('interest_calculation_frequency')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('account-types.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn-primary">
                        Save Account Type
                    </button>
                </div>
            </section>
        </form>
    </section>
@endsection
