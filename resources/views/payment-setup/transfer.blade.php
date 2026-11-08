
@extends('layouts.app')

@section('title', 'Transfer Balance')
@section('page-title', 'Transfer Balance')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Transfer Balance</h1>
                <p>Transfer balance from one payment method to another.</p>
            </div>

            <a href="{{ route('payment-setup.index') }}" class="member-add-btn">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back</span>
            </a>
        </div>

        <div class="members-card" style="padding: 25px;">
            <form action="{{ route('payment-setup.transfer.store') }}" method="POST" id="transferForm">
                @csrf

                <div class="transfer-grid">
                    <div class="transfer-field">
                        <label for="from_payment_method">From Payment Method</label>

                        <select
                            name="from_payment_method"
                            id="from_payment_method"
                            required
                        >
                            <option value="">Select source payment method</option>

                            @foreach($paymentMethods as $pm)
                                <option
                                    value="{{ $pm->getId() }}"
                                    data-balance="{{ $pm->balance }}"
                                    {{ old('from_payment_method') == $pm->getId() ? 'selected' : '' }}
                                >
                                    {{ $pm->name }} (Balance: {{ number_format($pm->balance, 2) }})
                                </option>
                            @endforeach
                        </select>

                        @error('from_payment_method')
                        <span class="transfer-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="transfer-field">
                        <label for="to_payment_method">To Payment Method</label>

                        <select
                            name="to_payment_method"
                            id="to_payment_method"
                            required
                        >
                            <option value="">Select destination payment method</option>

                            @foreach($paymentMethods as $pm)
                                <option
                                    value="{{ $pm->getId() }}"
                                    {{ old('to_payment_method') == $pm->getId() ? 'selected' : '' }}
                                >
                                    {{ $pm->name }} (Balance: {{ number_format($pm->balance, 2) }})
                                </option>
                            @endforeach
                        </select>

                        @error('to_payment_method')
                        <span class="transfer-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="transfer-balance-info" id="balanceInfo" style="display: none;">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Available Balance:</span>
                    <strong id="availableBalance">0.00</strong>
                </div>

                <div class="transfer-field transfer-amount-field">
                    <label for="amount">Transfer Amount</label>

                    <input
                        type="number"
                        name="amount"
                        id="amount"
                        placeholder="Enter transfer amount"
                        min="0.01"
                        step="0.01"
                        value="{{ old('amount') }}"
                        required
                        disabled
                    >

                    <small id="amountHelp">
                        Select a source payment method first.
                    </small>

                    @error('amount')
                    <span class="transfer-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="transfer-actions">
                    <a href="{{ route('payment-setup.index') }}" class="transfer-cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="member-add-btn" id="transferSubmit" disabled>
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        Transfer Balance
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])

    <style>
        .transfer-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 22px;
        }

        .transfer-field {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 20px;
        }

        .transfer-field label {
            font-weight: 600;
            color: #333;
        }

        .transfer-field select,
        .transfer-field input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 7px;
            outline: none;
            background: #fff;
            font-size: 14px;
        }

        .transfer-field select:focus,
        .transfer-field input:focus {
            border-color: #3085d6;
        }

        .transfer-field select:disabled,
        .transfer-field input:disabled {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .transfer-balance-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            margin-bottom: 22px;
            background: #f0f7ff;
            border: 1px solid #d5e8ff;
            border-radius: 7px;
            color: #245b91;
        }

        .transfer-balance-info strong {
            margin-left: auto;
            font-size: 16px;
        }

        .transfer-amount-field {
            max-width: 500px;
        }

        .transfer-amount-field small {
            color: #777;
        }

        .transfer-error {
            color: #dc3545;
            font-size: 13px;
        }

        .transfer-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        .transfer-cancel-btn {
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 7px;
            text-decoration: none;
            color: #444;
            font-weight: 600;
        }

        .transfer-actions button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 650px) {
            .transfer-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .transfer-actions {
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const fromSelect = document.getElementById('from_payment_method');
        const toSelect = document.getElementById('to_payment_method');
        const amountInput = document.getElementById('amount');
        const balanceInfo = document.getElementById('balanceInfo');
        const availableBalance = document.getElementById('availableBalance');
        const amountHelp = document.getElementById('amountHelp');
        const transferSubmit = document.getElementById('transferSubmit');

        function updateTransferForm(changed) {
            const fromId = fromSelect.value;
            const toId = toSelect.value;

            if (fromId && fromId === toId) {
                if (changed === 'from') {
                    toSelect.value = '';
                } else {
                    fromSelect.value = '';
                }
            }

            Array.from(toSelect.options).forEach(option => {
                option.disabled = option.value !== '' && option.value === fromSelect.value;
            });

            Array.from(fromSelect.options).forEach(option => {
                option.disabled = option.value !== '' && option.value === toSelect.value;
            });

            const selectedOption = fromSelect.options[fromSelect.selectedIndex];
            const balance = selectedOption && selectedOption.dataset.balance !== undefined
                ? Number(selectedOption.dataset.balance)
                : 0;

            const hasSource = fromSelect.value !== '';
            const hasDestination = toSelect.value !== '';
            const validBalance = Number.isFinite(balance) && balance > 0;

            if (hasSource) {
                balanceInfo.style.display = 'flex';
                availableBalance.textContent = balance.toFixed(2);

                amountInput.disabled = !validBalance;
                amountInput.max = balance;
                amountInput.min = '0.01';

                if (Number(amountInput.value) > balance) {
                    amountInput.value = balance;
                }

                amountHelp.textContent = validBalance
                    ? 'Maximum transferable amount: ' + balance.toFixed(2)
                    : 'This payment method has no available balance.';
            } else {
                balanceInfo.style.display = 'none';
                amountInput.value = '';
                amountInput.disabled = true;
                amountInput.removeAttribute('max');
                amountHelp.textContent = 'Select a source payment method first.';
            }

            transferSubmit.disabled = !(
                hasSource &&
                hasDestination &&
                fromSelect.value !== toSelect.value &&
                validBalance &&
                Number(amountInput.value) > 0 &&
                Number(amountInput.value) <= balance
            );
        }

        fromSelect.addEventListener('change', () => {
            amountInput.value = '';
            updateTransferForm('from');
        });

        toSelect.addEventListener('change', () => {
            updateTransferForm('to');
        });

        amountInput.addEventListener('input', () => {
            const max = Number(amountInput.max);
            let value = Number(amountInput.value);

            if (amountInput.value !== '' && value > max) {
                amountInput.value = max;
            }

            if (amountInput.value !== '' && value < 0) {
                amountInput.value = '';
            }

            updateTransferForm();
        });

        document.getElementById('transferForm').addEventListener('submit', function (e) {
            const fromId = fromSelect.value;
            const toId = toSelect.value;
            const amount = Number(amountInput.value);
            const max = Number(amountInput.max);

            if (
                !fromId ||
                !toId ||
                fromId === toId ||
                !Number.isFinite(amount) ||
                amount <= 0 ||
                amount > max
            ) {
                e.preventDefault();
                alert('Please select valid payment methods and enter a valid transfer amount.');
            }
        });

        updateTransferForm();
    </script>
@endpush
