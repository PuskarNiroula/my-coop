
@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Add Product</h1>
                <p>Create a new product.</p>
            </div>

            <a href="{{ route('products.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Products</span>
            </a>
        </div>

        <div class="members-card">
            <form action="{{ route('products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
                @csrf

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
                            value="{{ old('name') }}"
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
                        <label for="product_type">Product Type <span>*</span></label>

                        <select name="product_type" id="product_type" class="form-control" required>
                            <option value="">Select Product Type</option>

                            @foreach($productTypes as $key => $value)
                                <option
                                    value="{{ $key }}"
                                    {{ old('product_type') == $key ? 'selected' : '' }}
                                >
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_type')
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
                            <option value="">Select Product Type First</option>
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
                        >{{ old('description') }}</textarea>

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
                                src=""
                                alt="Product image preview"
                                style="display: none; width: 120px; height: 120px; object-fit: cover; border-radius: 8px;"
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
                        <span>Save Product</span>
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
        const productTypeSelect = document.getElementById('product_type');
        const baseUnitSelect = document.getElementById('base_unit');

        const unitsUrl = @json(route('inventory.units.get-units', ['unitCategoryId' => '__CATEGORY_ID__']));
        const oldBaseUnit = @json(old('base_unit'));

        imageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                return;
            }

            if (!file.type.startsWith('image/')) {
                this.value = '';
                imagePreview.src = '';
                imagePreview.style.display = 'none';

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

        async function loadUnits(categoryId, selectedUnit = '') {
            baseUnitSelect.innerHTML = '<option value="">Loading units...</option>';
            baseUnitSelect.disabled = true;

            if (!categoryId) {
                baseUnitSelect.innerHTML = '<option value="">Select Product Type First</option>';
                return;
            }

            try {
                const url = unitsUrl.replace('__CATEGORY_ID__', encodeURIComponent(categoryId));

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
                    baseUnitSelect.innerHTML = '<option value="">No units available for this category</option>';
                    return;
                }

                units.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.unit_id;
                    option.textContent = unit.name;

                    if (String(unit.unit_id) === String(selectedUnit)) {
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

        productTypeSelect.addEventListener('change', function() {
            loadUnits(this.value);
        });

        if (productTypeSelect.value) {
            loadUnits(productTypeSelect.value, oldBaseUnit);
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const name = document.getElementById('name').value.trim();
            const productType = productTypeSelect.value;
            const baseUnit = baseUnitSelect.value;

            if (!name || !productType || !baseUnit) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    text: 'Please enter product name and select product type and base unit.'
                });

                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to create this product?',
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
