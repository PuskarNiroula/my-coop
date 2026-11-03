@extends('layouts.app')

@section('title', 'Edit Expense Head')
@section('page-title', "Edit Expense Head")

@section("content")

    <div class="member-page">
        <div class="page-heading">
            <div>
                <span class="page-eyebrow">Expense management</span>
                <h1>Add Expense Head</h1>
                <p>Create a new expense category for your organization.</p>
            </div>

            <a href="{{ url('/expense-head') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Expense Heads
            </a>
        </div>

        <form id="expenseHeadForm" action="{{ route('expenses.store') }}" method="POST" novalidate>
            @csrf

            <div class="member-main-column">

                <div class="form-card">
                    <div class="form-card-heading">
                        <div class="section-icon">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h2>Expense Head Information</h2>
                            <p>Enter the name of the expense head.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name">Expense Head Name <span>*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter expense head name"
                                required
                                autofocus
                            >

                            @error('name')
                            <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="btn-primary" id="submitExpenseHead">
                    <i class="fa-solid fa-check"></i>
                    Save Expense Head
                </button>
            </div>


        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('expenseHeadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const name = document.getElementById('name').value.trim();

            if (!name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Expense Head Required',
                    text: 'Please enter an expense head name.'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to create "${name}" as an expense head?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Create It',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
