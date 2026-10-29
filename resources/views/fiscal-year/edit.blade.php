@extends('layouts.app')

@section('title', isset($fiscalYear) ? 'Update Fiscal Year' : 'Add Fiscal Year')
@section('page-title', isset($fiscalYear) ? 'Update Fiscal Year' : 'Add Fiscal Year')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>{{ isset($fiscalYear) ? 'Update Fiscal Year' : 'Add Fiscal Year' }}</h1>
                <p>{{ isset($fiscalYear) ? 'Update fiscal year details.' : 'Create a new fiscal year.' }}</p>
            </div>

            <a href="{{ route('fiscal-year.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Fiscal Years</span>
            </a>
        </div>

        <div class="members-card">
            <form
                action="{{ isset($fiscalYear) ? route('fiscal-year.update', $fiscalYear->fiscal_year_id) : route('fiscal-year.store') }}"
                method="POST"
            >
                @csrf

                @if(isset($fiscalYear))
                    @method('PUT')
                @endif

                <div class="members-toolbar">
                    <h3>Fiscal Year Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Fiscal Year Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $fiscalYear->name ?? '') }}"
                            placeholder="e.g. 2083/84"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sequence_number">Sequence Number <span>*</span></label>

                        <input
                            type="number"
                            name="sequence_number"
                            id="sequence_number"
                            class="form-control"
                            value="{{ old('sequence_number', $fiscalYear->sequence ?? '') }}"
                            placeholder="e.g. 1"
                            min="1"
                            required
                        >

                        @error('sequence_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('fiscal-year.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>{{ isset($fiscalYear) ? 'Update Fiscal Year' : 'Save Fiscal Year' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
