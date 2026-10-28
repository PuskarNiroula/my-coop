@extends('layouts.app')

@section('title', 'New Deposit')
@section('page-title', 'New Deposit')

@section('content')
    <div class="members-page deposit-page">
        <div class="members-heading">
            <div>
                <h1>New Savings Deposit</h1>
                <p>Record a savings deposit for a member of your cooperative.</p>
            </div>

            <a href="{{ url('/savings') }}" class="member-add-btn">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Savings</span>
            </a>
        </div>

        <div class="members-card deposit-card">
            <div class="deposit-card-header">
                <div class="deposit-header-icon">
                    <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                </div>
                <div>
                    <h2>Deposit Details</h2>
                    <p>Fill in the details below to record a deposit.</p>
                </div>
            </div>

            <form action="{{ url('/savings/deposit') }}" method="POST" id="depositForm" novalidate>
                @csrf

                <div class="deposit-form-grid">
                    <div class="deposit-field deposit-field-full">
                        <label for="member_id">Select Member <span>*</span></label>
                        <select name="member_id" id="member_id" required>
                            <option value="">Choose a member</option>
                            @foreach($members as $member)
                                <option
                                    value="{{ $member->member_id }}"
                                    {{ (string) old('member_id') === (string) $member->member_id ? 'selected' : '' }}
                                >
                                    {{ $member->name }}@if(isset($member->member_code)) ({{ $member->member_code }})@endif
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                        <small class="deposit-error" role="alert">{{ $message }}</small>
                        @enderror
                        <small class="deposit-error deposit-client-error" data-error-for="member_id" role="alert" hidden></small>
                    </div>

                    <div class="deposit-field deposit-field-full">
                        <div class="deposit-label-row">
                            <span class="deposit-label" id="deposit-month-label">Select Deposit Month <span>*</span></span>
                            <span class="deposit-hint">Select one month only</span>
                        </div>

                        <div class="deposit-months" role="radiogroup" aria-labelledby="deposit-month-label">
                            @foreach($monthArray as $monthId => $monthName)
                                <label class="deposit-month-option">
                                    <input
                                        type="radio"
                                        name="month_id"
                                        value="{{ $monthId }}"
                                        {{ (string) old('month_id') === (string) $monthId ? 'checked' : '' }}
                                        required
                                    >
                                    <span class="month-radio"></span>
                                    <span class="month-name">{{ $monthName }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('month_id')
                        <small class="deposit-error" role="alert">{{ $message }}</small>
                        @enderror
                        <small class="deposit-error deposit-client-error" data-error-for="month_id" role="alert" hidden></small>
                    </div>

                    <div class="deposit-field">
                        <label for="amount">Deposit Amount <span>*</span></label>
                        <div class="deposit-amount-wrapper">
                            <span class="currency-prefix">Rs.</span>
                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                min="0.01"
                                step="0.01"
                                inputmode="decimal"
                                value="{{ old('amount') }}"
                                placeholder="0.00"
                                required
                            >
                        </div>
                        @error('amount')
                        <small class="deposit-error" role="alert">{{ $message }}</small>
                        @enderror
                        <small class="deposit-error deposit-client-error" data-error-for="amount" role="alert" hidden></small>
                    </div>

                    <div class="deposit-field deposit-field-full">
                        <label for="description">Description <span class="optional-label">(Optional)</span></label>
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            maxlength="1000"
                            placeholder="Enter deposit remarks or additional details..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                        <small class="deposit-error" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="deposit-form-footer">
                    <p><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Review the details before confirming the deposit.</p>

                    <div class="deposit-form-actions">
                        <a href="{{ url('/savings') }}" class="deposit-cancel-btn">Cancel</a>

                        <button type="submit" class="deposit-submit-btn">
                            <i class="fa-solid fa-check" aria-hidden="true"></i>
                            <span>Save Deposit</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('depositForm');

            if (!form) {
                return;
            }

            const memberSelect = form.querySelector('#member_id');
            const amountInput = form.querySelector('#amount');
            const descriptionInput = form.querySelector('#description');
            const submitButton = form.querySelector('.deposit-submit-btn');

            const escapeHtml = function (value) {
                const element = document.createElement('div');
                element.textContent = value;
                return element.innerHTML;
            };

            const showError = function (field, message) {
                const target = form.querySelector('[data-error-for="' + field + '"]');

                if (!target) {
                    return;
                }

                target.textContent = message;
                target.hidden = false;
            };

            const clearErrors = function () {
                form.querySelectorAll('.deposit-client-error').forEach(function (element) {
                    element.textContent = '';
                    element.hidden = true;
                });
            };

            const validate = function () {
                clearErrors();

                let firstInvalid = null;

                if (!memberSelect.value) {
                    showError('member_id', 'Please select a member.');
                    firstInvalid = firstInvalid || memberSelect;
                }

                if (!form.querySelector('input[name="month_id"]:checked')) {
                    showError('month_id', 'Please select one month for this deposit.');
                    firstInvalid = firstInvalid || form.querySelector('input[name="month_id"]');
                }

                const amountValue = parseFloat(amountInput.value);

                if (!Number.isFinite(amountValue) || amountValue < 0.01) {
                    showError('amount', 'Enter an amount greater than zero.');
                    firstInvalid = firstInvalid || amountInput;
                }

                if (firstInvalid) {
                    firstInvalid.focus();
                    return false;
                }

                return true;
            };

            amountInput.addEventListener('wheel', function (event) {
                event.preventDefault();
            }, { passive: false });

            form.addEventListener('input', clearErrors);
            form.addEventListener('change', clearErrors);

            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();

                if (!validate()) {
                    return;
                }

                if (typeof Swal === 'undefined') {
                    form.dataset.confirmed = 'true';
                    submitButton.disabled = true;
                    form.submit();
                    return;
                }

                const selectedMonth = form.querySelector('input[name="month_id"]:checked');
                const memberName = memberSelect.options[memberSelect.selectedIndex].text.replace(/\s+/g, ' ').trim();
                const monthName = selectedMonth.closest('label').querySelector('.month-name').textContent.trim();
                const amount = Number(amountInput.value).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const description = descriptionInput.value.trim();

                Swal.fire({
                    title: 'Confirm Deposit',
                    html: `
                        <div style="text-align:left;line-height:1.7">
                            <p style="margin:0 0 6px"><strong>Member:</strong> ${escapeHtml(memberName)}</p>
                            <p style="margin:0 0 6px"><strong>Month:</strong> ${escapeHtml(monthName)}</p>
                            <p style="margin:0 0 6px"><strong>Amount:</strong> Rs. ${escapeHtml(amount)}</p>
                            ${description ? `<p style="margin:0 0 6px;white-space:pre-wrap"><strong>Description:</strong> ${escapeHtml(description)}</p>` : ''}
                            <p style="margin:12px 0 0">Are you sure you want to save this deposit?</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Save Deposit',
                    cancelButtonText: 'Review Again',
                    confirmButtonColor: '#15803d',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    form.dataset.confirmed = 'true';
                    submitButton.disabled = true;
                    form.submit();
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        #amount::-webkit-inner-spin-button,
        #amount::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #amount {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .deposit-page {
            --deposit-primary: var(--primary-color, #15803d);
            --deposit-primary-dark: var(--primary-dark, #166534);
            --deposit-border: #dce7dd;
            --deposit-muted: #647568;
            --deposit-text: #1f3024;
            --deposit-surface: #ffffff;
            --deposit-background: #f5f9f5;
        }

        .deposit-card {
            overflow: hidden;
            padding: 0;
            border: 1px solid var(--deposit-border);
            border-radius: 12px;
            background: var(--deposit-surface);
            box-shadow: 0 2px 8px rgba(22, 101, 52, 0.05);
        }

        .deposit-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 26px;
            border-bottom: 1px solid var(--deposit-border);
            background: linear-gradient(135deg, #f0f8f1, #ffffff);
        }

        .deposit-header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: #e5f3e8;
            color: var(--deposit-primary);
            font-size: 20px;
        }

        .deposit-card-header h2 {
            margin: 0 0 5px;
            color: var(--deposit-text);
            font-size: 18px;
            font-weight: 700;
        }

        .deposit-card-header p {
            margin: 0;
            color: var(--deposit-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .deposit-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            padding: 26px;
        }

        .deposit-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .deposit-field-full {
            grid-column: 1 / -1;
        }

        .deposit-field > label,
        .deposit-label {
            color: var(--deposit-text);
            font-size: 13px;
            font-weight: 600;
        }

        .deposit-field > label span,
        .deposit-label span {
            color: #dc2626;
        }

        .deposit-field .optional-label {
            color: #849487;
            font-size: 12px;
            font-weight: 400;
        }

        .deposit-field input:not([type="radio"]),
        .deposit-field select,
        .deposit-field textarea {
            box-sizing: border-box;
            width: 100%;
            min-height: 43px;
            padding: 10px 13px;
            border: 1px solid #cbd9cd;
            border-radius: 7px;
            outline: none;
            background: #ffffff;
            color: var(--deposit-text);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .deposit-field input::placeholder,
        .deposit-field textarea::placeholder {
            color: #94a398;
        }

        .deposit-field input:hover,
        .deposit-field select:hover,
        .deposit-field textarea:hover {
            border-color: #a9c4ae;
        }

        .deposit-field input:focus,
        .deposit-field select:focus,
        .deposit-field textarea:focus {
            border-color: var(--deposit-primary);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.1);
        }

        .deposit-field textarea {
            min-height: 100px;
            resize: vertical;
            line-height: 1.6;
        }

        .deposit-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .deposit-hint {
            color: var(--deposit-muted);
            font-size: 12px;
        }

        .deposit-months {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .deposit-month-option {
            position: relative;
            display: flex;
            align-items: center;
            gap: 9px;
            min-height: 44px;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid var(--deposit-border);
            border-radius: 7px;
            background: #ffffff;
            color: #47594b;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .deposit-month-option:hover {
            border-color: #86b99a;
            background: #f5faf6;
        }

        .deposit-month-option input[type="radio"] {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
        }

        .month-radio {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 16px;
            height: 16px;
            box-sizing: border-box;
            border: 1.5px solid #a3b5a6;
            border-radius: 50%;
            background: #ffffff;
        }

        .month-name {
            font-size: 13px;
            font-weight: 500;
        }

        .deposit-month-option:has(input:checked) {
            border-color: var(--deposit-primary);
            background: #edf7ef;
            color: var(--deposit-primary-dark);
            box-shadow: inset 0 0 0 1px var(--deposit-primary);
        }

        .deposit-month-option input:checked + .month-radio {
            border: 5px solid var(--deposit-primary);
        }

        .deposit-month-option input:focus-visible + .month-radio {
            outline: 3px solid rgba(21, 128, 61, 0.2);
            outline-offset: 3px;
        }

        .deposit-amount-wrapper {
            display: flex;
            align-items: stretch;
            min-height: 43px;
            overflow: hidden;
            border: 1px solid #cbd9cd;
            border-radius: 7px;
            background: #ffffff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .deposit-amount-wrapper:hover {
            border-color: #a9c4ae;
        }

        .deposit-amount-wrapper:focus-within {
            border-color: var(--deposit-primary);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.1);
        }

        .deposit-amount-wrapper .currency-prefix {
            display: flex;
            align-items: center;
            padding: 0 14px;
            border-right: 1px solid var(--deposit-border);
            background: #f1f7f2;
            color: #47634c;
            font-size: 13px;
            font-weight: 600;
        }

        .deposit-amount-wrapper input:not([type="radio"]),
        .deposit-amount-wrapper input:not([type="radio"]):hover,
        .deposit-amount-wrapper input:not([type="radio"]):focus {
            min-width: 0;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .deposit-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 26px;
            border-top: 1px solid var(--deposit-border);
            background: var(--deposit-background);
        }

        .deposit-form-footer > p {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            color: var(--deposit-muted);
            font-size: 12px;
        }

        .deposit-form-footer > p i {
            color: var(--deposit-primary);
        }

        .deposit-form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .deposit-cancel-btn,
        .deposit-submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }

        .deposit-cancel-btn {
            border-color: #cbd9cd;
            background: #ffffff;
            color: #47594b;
        }

        .deposit-cancel-btn:hover {
            border-color: #9eb6a2;
            background: #f0f6f1;
        }

        .deposit-submit-btn {
            border-color: var(--deposit-primary);
            background: var(--deposit-primary);
            color: #ffffff;
        }

        .deposit-submit-btn:hover {
            border-color: var(--deposit-primary-dark);
            background: var(--deposit-primary-dark);
            transform: translateY(-1px);
        }

        .deposit-submit-btn:focus-visible,
        .deposit-cancel-btn:focus-visible {
            outline: 3px solid rgba(21, 128, 61, 0.2);
            outline-offset: 2px;
        }

        .deposit-submit-btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            transform: none;
        }

        .deposit-error {
            color: #dc2626;
            font-size: 12px;
        }

        .deposit-error[hidden] {
            display: none;
        }

        @media (max-width: 900px) {
            .deposit-months {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .deposit-form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 18px;
            }

            .deposit-field-full {
                grid-column: auto;
            }

            .deposit-months {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .deposit-month-option {
                padding: 10px;
            }

            .deposit-card-header {
                padding: 18px;
            }

            .deposit-header-icon {
                width: 42px;
                height: 42px;
            }

            .deposit-form-footer {
                align-items: stretch;
                padding: 18px;
            }

            .deposit-form-actions {
                width: 100%;
            }

            .deposit-cancel-btn,
            .deposit-submit-btn {
                flex: 1;
            }

            .deposit-label-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 380px) {
            .deposit-months {
                grid-template-columns: 1fr;
            }

            .deposit-form-grid {
                padding: 14px;
            }

            .deposit-form-actions {
                flex-direction: column;
            }

            .deposit-cancel-btn,
            .deposit-submit-btn {
                width: 100%;
            }
        }
    </style>
@endpush
