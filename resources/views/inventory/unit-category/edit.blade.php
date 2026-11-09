@extends('layouts.app')

@section('title', 'Edit Unit Group')
@section('page-title', 'Edit Unit Group')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Unit Group</h1>
                <p>Update the unit group details.</p>
            </div>

            <a href="{{ route('inventory.category.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Unit Groups</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('inventory.category.update', $category->unit_category_id) }}" method="POST" id="unitGroupForm">
            <form action="{{ route('inventory.category.update', $category->unit_category_id) }}" method="POST" id="unitGroupForm">
                @csrf
                @method('PUT')

                <div class="members-toolbar">
                    <h3>Unit Group Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Unit Group Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $category->name) }}"
                            placeholder="e.g. Weight, Volume, Length"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="base_unit_id">Base Unit</label>

                        <select name="base_unit_id" id="base_unit_id" class="form-control">
                            <option value="">Select Base Unit (Optional)</option>

                            @foreach($units as $unit)
                                <option
                                    value="{{ $unit->getId() }}"
                                    {{ old('base_unit_id', $category->base_unit_id) == $unit->getId() ? 'selected' : '' }}
                                >
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">
                            Leave blank if the base unit has not been created yet.
                        </small>

                        @error('base_unit_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('inventory.category.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Update Unit Group</span>
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
        document.getElementById('unitGroupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this unit group?',
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
