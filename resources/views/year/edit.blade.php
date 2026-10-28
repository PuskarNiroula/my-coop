
@extends('layouts.app')

@section('title', 'Edit Year')
@section('page-title', 'Edit Year')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Year</h1>
                <p>Update year details.</p>
            </div>

            <a href="{{ route('year.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Years</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('year.update', $year->year_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="members-toolbar">
                    <h3>Year Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Year Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $year->name) }}"
                            placeholder="Enter year name"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sequence">Sequence <span>*</span></label>

                        <input
                            type="number"
                            name="sequence"
                            id="sequence"
                            class="form-control"
                            value="{{ old('sequence', $year->sequence) }}"
                            placeholder="Enter sequence"
                            min="1"
                            required
                        >

                        @error('sequence')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('year.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Update Year</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush
