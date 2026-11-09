@extends('layouts.app')

@section('title', 'Add Unit')
@section('page-title', 'Add Unit')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Add Unit</h1>
                <p>Create a new unit.</p>
            </div>

            <a href="{{ route('inventory.units.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Units</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('inventory.units.store') }}" method="POST" id="unitForm">
                @csrf

                <div class="members-toolbar">
                    <h3>Unit Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">
                    <div class="form-group">
                        <label for="name">Unit Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="e.g. Kilogram, Gram, Liter"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="symbol">Unit Symbol <span>*</span></label>

                        <input
                            type="text"
                            name="symbol"
                            id="symbol"
                            class="form-control"
                            value="{{ old('symbol') }}"
                            placeholder="e.g. kg, g, L"
                            maxlength="50"
                            required
                        >

                        @error('symbol')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_id">Unit Group <span>*</span></label>

                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Unit Group</option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->getId() }}"
                                    {{ old('category_id') == $category->getId() ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="conversion_rate">Conversion Rate <span>*</span></label>

                        <input
                            type="number"
                            name="conversion_rate"
                            id="conversion_rate"
                            class="form-control"
                            value="{{ old('conversion_rate') }}"
                            placeholder="e.g. 1, 1000, 0.001"
                            step="any"
                            min="0.00000001"
                            required
                        >

                        <small class="text-muted">
                            Enter the conversion rate relative to the base unit of the selected unit group.
                        </small>

                        @error('conversion_rate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="members-toolbar">
                    <a href="{{ route('inventory.units.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Save Unit</span>
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
        document.getElementById('unitForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to create this unit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
