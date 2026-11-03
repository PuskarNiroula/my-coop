@extends('layouts.app')

@section('title', 'Create Expense Record')
@section('page-title', 'Create Expense Record')

@section('content')

    <div class="member-page">
        <div class="page-heading">
            <div>
                <span class="page-eyebrow">Expense management</span>
                <h1>Create Expense Record</h1>
                <p>Record a new business expense.</p>
            </div>

            <a href="{{ route('expense.records.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Expense Records
            </a>
        </div>

        <form id="expenseRecordForm" action="{{ route('expense.records.store') }}" method="POST" novalidate>
            @csrf

            <div class="member-main-column">

                <div class="form-card">
                    <div class="form-card-heading">
                        <div class="section-icon">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <h2>Expense Information</h2>
                            <p>Enter the expense head and transaction details.</p>
                        </div>
                    </div>

                    <div class="form-grid">

                        <div class="form-group full-width">
                            <label for="expense_head_id">Expense Head <span>*</span></label>

                            <select id="expense_head_id" name="expense_head_id" required>
                                <option value="">Select Expense Head</option>

                                @foreach($expenseHeads as $expenseHead)
                                    <option
                                        value="{{ $expenseHead->getId() }}"
                                        {{ old('expense_head_id') == $expenseHead->getId() ? 'selected' : '' }}
                                    >
                                        {{ $expenseHead->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('expense_head_id')
                            <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="amount">Amount <span>*</span></label>

                            <input
                                type="number"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="Enter expense amount"
                                min="0.01"
                                step="0.01"
                                required
                            >

                            @error('amount')
                            <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Description <small>(Optional)</small></label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Enter expense description (optional)"
                            >{{ old('description') }}</textarea>

                            @error('description')
                            <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('expense.records.index') }}" class="btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn-primary" id="submitExpenseRecord">
                    <i class="fa-solid fa-check"></i>
                    Save Expense Record
                </button>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('expenseRecordForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const expenseHead = document.getElementById('expense_head_id');
            const amount = document.getElementById('amount');

            if (!expenseHead.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Expense Head Required',
                    text: 'Please select an expense head.'
                });
                return;
            }

            if (!amount.value || Number(amount.value) <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Amount',
                    text: 'Please enter a valid expense amount.'
                });
                return;
            }

            const selectedHead = expenseHead.options[expenseHead.selectedIndex].text;

            Swal.fire({
                title: 'Confirm Expense Record?',
                html: `
                <div style="text-align:left">
                    <p><strong>Expense Head:</strong> ${selectedHead}</p>
                    <p><strong>Amount:</strong> ${Number(amount.value).toFixed(2)}</p>
                    <p>Are you sure you want to save this expense record?</p>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save Record',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
