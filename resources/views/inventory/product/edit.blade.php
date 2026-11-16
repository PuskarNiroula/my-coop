
@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Edit Product</h1>
                <p>Update product details.</p>
            </div>

            <a href="{{ route('products.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Products</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('products.update', $product->getId()) }}" method="POST" id="productForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="members-toolbar">
                    <h3>Product Details</h3>
                </div>

                <div class="members-table-wrapper" style="padding: 24px;">

                    <div class="form-group">
                        <label for="name">Product Name <span>*</span></label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $product->name) }}"
                            placeholder="e.g. Rice"
                            maxlength="255"
                            required
                            autofocus
                        >

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="base_unit">Base Unit <span>*</span></label>

                        <select
                            name="base_unit"
                            id="base_unit"
                            class="form-control"
                            required
                            disabled
                        >
                            <option value="">Loading units...</option>
                        </select>

                        @error('base_unit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>

                        <textarea
                            name="description"
                            id="description"
                            class="form-control"
                            rows="4"
                            placeholder="Enter product description"
                            maxlength="1000"
                        >{{ old('description', $product->description) }}</textarea>

                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            accept="image/*"
                        >

                        <div style="margin-top: 12px;">
                            <img
                                id="imagePreview"
                                src="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                alt="Product image preview"
                                style="{{ $product->image ? 'display: block;' : 'display: none;' }} width: 120px; height: 120px; object-fit: cover; border-radius: 8px;"
                            >
                        </div>

                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="members-toolbar">
                    <a href="{{ route('products.index') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Update Product</span>
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
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const baseUnitSelect = document.getElementById('base_unit');

        const unitCategoryId = @json($product->product_type);
        const currentBaseUnit = @json(old('base_unit', $product->base_unit_id));
        const unitsUrl = @json(route('inventory.units.get-units', ['unitCategoryId' => '__CATEGORY_ID__']));

        imageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {
                this.value = '';

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Image',
                    text: 'Please select a valid image file.'
                });

                return;
            }

            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        });

        async function loadUnits() {
            baseUnitSelect.innerHTML = '<option value="">Loading units...</option>';
            baseUnitSelect.disabled = true;

            try {
                const url = unitsUrl.replace(
                    '__CATEGORY_ID__',
                    encodeURIComponent(unitCategoryId)
                );

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unable to fetch units.');
                }

                const units = await response.json();

                baseUnitSelect.innerHTML = '<option value="">Select Base Unit</option>';

                if (units.length === 0) {
                    baseUnitSelect.innerHTML = '<option value="">No units available</option>';
                    return;
                }

                units.forEach(unit => {
                    const option = document.createElement('option');

                    option.value = unit.unit_id;
                    option.textContent = unit.name;

                    if (String(unit.unit_id) === String(currentBaseUnit)) {
                        option.selected = true;
                    }

                    baseUnitSelect.appendChild(option);
                });

                baseUnitSelect.disabled = false;

            } catch (error) {
                baseUnitSelect.innerHTML = '<option value="">Failed to load units</option>';

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to load units. Please try again.'
                });
            }
        }

        loadUnits();

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const name = document.getElementById('name').value.trim();
            const baseUnit = baseUnitSelect.value;

            if (!name || !baseUnit || baseUnitSelect.disabled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    text: 'Please enter product name and select a base unit.'
                });

                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this product?',
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
