@extends('layouts.app')

@section('title', 'Add Fiscal Year')
@section('page-title', 'Add Fiscal Year')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Add Fiscal Year</h1>
                <p>Create a new fiscal year.</p>
            </div>

            <a href="{{ route('fiscal-year.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Fiscal Years</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('fiscal-year.store') }}" method="POST">
                @csrf

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
                            value="{{ old('name') }}"
                            placeholder="e.g. 2083/84"
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
                    <a href="{{ route('fiscal-year.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Save Fiscal Year</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
