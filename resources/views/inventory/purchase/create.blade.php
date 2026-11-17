```blade
@extends('layouts.app')

@section('title', 'New Purchase')
@section('page-title', 'New Purchase')

@section('content')

    @vite(['resources/css/purchase_create.css'])

    <div class="purchase-page">

        <div class="page-heading">
            <div>
                <span class="page-eyebrow">Inventory management</span>
                <h1>New Purchase</h1>
                <p>Record inventory purchases and manage product pricing.</p>
            </div>

            <a href="{{ route('purchases.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Purchases
            </a>
        </div>

        <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST" novalidate>
            @csrf

            <div class="purchase-layout">

                <div class="purchase-main-column">

                    <div class="form-card">

                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div>
                                <h2>Purchase Information</h2>
                                <p>Select supplier and product details.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label for="supplier_id">Supplier <span>*</span></label>
                                <select name="supplier_id" id="supplier_id" required>
                                    <option value="">Select supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_id">Product <span>*</span></label>
                                <select name="product_id" id="product_id" required>
                                    <option value="">Select product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="purchase_unit_id">Purchase Unit <span>*</span></label>
                                <select name="purchase_unit_id" id="purchase_unit_id" required disabled>
                                    <option value="">Select product first</option>
                                </select>
                                @error('purchase_unit_id')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity">Purchase Quantity <span>*</span></label>
                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       value="{{ old('quantity') }}"
                                       placeholder="Enter quantity"
                                       min="0.001"
                                       step="any"
                                       required>
                                @error('quantity')
                                <small class="field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <div class="form-card">

                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <div>
                                <h2>Purchase Pricing</h2>
                                <p>Enter purchase price or rate. Both values calculate automatically.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label for="purchase_price">Purchase Price (Total) <span>*</span></label>
                                <input type="number"
                                       name="purchase_price"
                                       id="purchase_price"
                                       value="{{ old('purchase_price') }}"
                                       placeholder="Enter total purchase price"
                                       min="0"
                                       step="any"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="purchase_rate">Purchase Rate (Per Unit) <span>*</span></label>
                                <input type="number"
                                       name="purchase_rate"
                                       id="purchase_rate"
                                       value="{{ old('purchase_rate') }}"
                                       placeholder="Enter rate per purchase unit"
                                       min="0"
                                       step="any"
                                       required>
                            </div>

                            <div class="form-group full-width">
                                <div class="pricing-note">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Changing the total purchase price calculates the unit rate. Changing the unit rate calculates the total price.</span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="form-card">

                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <div>
                                <h2>Selling Information</h2>
                                <p>Set the selling rate and its applicable unit.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group">
                                <label for="selling_rate">Selling Rate <span>*</span></label>
                                <input type="number"
                                       name="selling_rate"
                                       id="selling_rate"
                                       value="{{ old('selling_rate') }}"
                                       placeholder="Enter selling rate"
                                       min="0"
                                       step="any"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="selling_unit_id">Selling Unit <span>*</span></label>
                                <select name="selling_unit_id" id="selling_unit_id" required disabled>
                                    <option value="">Select product first</option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <div class="selling-preview" id="selling-preview">
                                    <i class="fa-solid fa-tag"></i>
                                    <span>Selling rate will appear here.</span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="form-card">

                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-align-left"></i>
                            </div>
                            <div>
                                <h2>Additional Information</h2>
                                <p>Add any additional purchase details.</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="description">Description</label>
                                <textarea name="description"
                                          id="description"
                                          rows="4"
                                          placeholder="Enter purchase description (optional)">{{ old('description') }}</textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="purchase-side-column">

                    <div class="form-card summary-card">

                        <div class="form-card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h2>Purchase Summary</h2>
                                <p>Review purchase details.</p>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span>Product</span>
                            <strong id="summary-product">Not selected</strong>
                        </div>

                        <div class="summary-row">
                            <span>Quantity</span>
                            <strong id="summary-quantity">0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Purchase Rate</span>
                            <strong id="summary-rate">0.00</strong>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <span>Total Purchase</span>
                            <strong id="summary-total">0.00</strong>
                        </div>

                        <div class="summary-info">
                            <i class="fa-solid fa-shield-halved"></i>
                            Please verify the product, quantity and pricing before saving.
                        </div>

                    </div>

                    <div class="form-card tips-card">

                        <div class="tips-title">
                            <i class="fa-solid fa-circle-info"></i>
                            Purchase Guidelines
                        </div>

                        <ul>
                            <li>Select the supplier before saving the purchase.</li>
                            <li>Product units are loaded automatically.</li>
                            <li>Enter either purchase price or purchase rate.</li>
                            <li>Choose the correct selling unit.</li>
                            <li>Verify all prices before submission.</li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('purchases.index') }}" class="btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn-primary" id="submitPurchase">
                    <i class="fa-solid fa-check"></i>
                    Save Purchase
                </button>
            </div>

        </form>

    </div>

@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('purchaseForm');

            const product = document.getElementById('product_id');
            const purchaseUnit = document.getElementById('purchase_unit_id');
            const sellingUnit = document.getElementById('selling_unit_id');

            const quantity = document.getElementById('quantity');
            const purchasePrice = document.getElementById('purchase_price');
            const purchaseRate = document.getElementById('purchase_rate');
            const sellingRate = document.getElementById('selling_rate');

            const sellingPreview = document.getElementById('selling-preview');

            let priceSource = null;
            let isSubmitting = false;

            function updatePurchasePricing(source) {

                const qty = parseFloat(quantity.value) || 0;

                if (qty <= 0) {
                    if (source === 'price') purchaseRate.value = '';
                    if (source === 'rate') purchasePrice.value = '';
                    updateSummary();
                    return;
                }

                if (source === 'price') {

                    const price = parseFloat(purchasePrice.value);

                    purchaseRate.value = purchasePrice.value === '' || !Number.isFinite(price)
                        ? ''
                        : (price / qty).toFixed(4);

                } else {

                    const rate = parseFloat(purchaseRate.value);

                    purchasePrice.value = purchaseRate.value === '' || !Number.isFinite(rate)
                        ? ''
                        : (rate * qty).toFixed(2);

                }

                updateSummary();
            }

            purchasePrice.addEventListener('input', function () {
                priceSource = 'price';
                updatePurchasePricing('price');
            });

            purchaseRate.addEventListener('input', function () {
                priceSource = 'rate';
                updatePurchasePricing('rate');
            });

            quantity.addEventListener('input', function () {
                if (priceSource) {
                    updatePurchasePricing(priceSource);
                } else {
                    updateSummary();
                }
            });

            function updateSummary() {

                const selectedProduct = product.options[product.selectedIndex];

                document.getElementById('summary-product').textContent =
                    selectedProduct && product.value ? selectedProduct.text : 'Not selected';

                document.getElementById('summary-quantity').textContent =
                    quantity.value || '0';

                document.getElementById('summary-rate').textContent =
                    Number(purchaseRate.value || 0).toFixed(2);

                document.getElementById('summary-total').textContent =
                    Number(purchasePrice.value || 0).toFixed(2);
            }

            function updateSellingPreview() {

                const rate = sellingRate.value;
                const selectedUnit = sellingUnit.options[sellingUnit.selectedIndex];

                if (!rate || !sellingUnit.value) {
                    sellingPreview.innerHTML = `
                <i class="fa-solid fa-tag"></i>
                <span>Selling rate will appear here.</span>
            `;
                    return;
                }

                sellingPreview.innerHTML = `
            <i class="fa-solid fa-tag"></i>
            <strong>Rs. ${Number(rate).toFixed(2)} / ${selectedUnit.text}</strong>
        `;
            }

            sellingRate.addEventListener('input', updateSellingPreview);
            sellingUnit.addEventListener('change', updateSellingPreview);

            function loadUnits(productId) {

                purchaseUnit.innerHTML = '<option value="">Loading units...</option>';
                sellingUnit.innerHTML = '<option value="">Loading units...</option>';

                purchaseUnit.disabled = true;
                sellingUnit.disabled = true;

                fetch(`/api/products/${productId}/units`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Unable to load units');
                        return response.json();
                    })
                    .then(data => {

                        const units = data.units || data;

                        purchaseUnit.innerHTML = '<option value="">Select purchase unit</option>';
                        sellingUnit.innerHTML = '<option value="">Select selling unit</option>';

                        units.forEach(unit => {

                            const purchaseOption = new Option(unit.name, unit.id);
                            const sellingOption = new Option(unit.name, unit.id);

                            purchaseUnit.add(purchaseOption);
                            sellingUnit.add(sellingOption);

                        });

                        purchaseUnit.disabled = false;
                        sellingUnit.disabled = false;

                        updateSellingPreview();

                    })
                    .catch(() => {

                        purchaseUnit.innerHTML = '<option value="">Unable to load units</option>';
                        sellingUnit.innerHTML = '<option value="">Unable to load units</option>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Unit Loading Failed',
                            text: 'Unable to load product units. Please try again.'
                        });

                    });

            }

            product.addEventListener('change', function () {

                purchaseUnit.innerHTML = '<option value="">Select product first</option>';
                sellingUnit.innerHTML = '<option value="">Select product first</option>';

                purchaseUnit.disabled = true;
                sellingUnit.disabled = true;

                if (this.value) {
                    loadUnits(this.value);
                }

                updateSummary();

            });

            if (product.value) {
                loadUnits(product.value);
            }

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                if (isSubmitting) return;

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: 'Confirm Purchase',
                    text: 'Are you sure you want to save this purchase?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Save Purchase',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    reverseButtons: true
                }).then(result => {

                    if (result.isConfirmed) {

                        isSubmitting = true;

                        const button = document.getElementById('submitPurchase');

                        button.disabled = true;

                        button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Saving...
                `;

                        form.submit();

                    }

                });

            });

            updateSummary();

        });
    </script>

@endpush
