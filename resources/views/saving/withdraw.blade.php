@extends('layouts.app')

@section('title', 'New Saving Withdrawal')
@section('page-title', 'New Saving Withdrawal')

@section('content')
    <div class="container-fluid px-4">
        <div class="member-page withdrawal-page">
            <div class="page-heading withdrawal-page-heading">
                <div>
                    <span class="page-eyebrow">SAVINGS MANAGEMENT</span>
                    <h1>New Withdrawal</h1>
                    <p>Process and record a withdrawal from a member's savings account.</p>
                </div>

                <a href="{{ url()->previous() }}" class="withdrawal-back-link">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>

            @if ($errors->any())
                <div class="flash-message flash-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        <strong>Please correct the following errors.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="flash-message flash-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="member-form-layout withdrawal-layout">
                <div class="member-main-column">
                    <div class="form-card withdrawal-form-card">
                        <div class="form-card-heading withdrawal-card-heading">
                            <div class="section-icon withdrawal-section-icon">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div>
                                <h2>Withdrawal Information</h2>
                                <p>Complete the details below to record a withdrawal.</p>
                            </div>
                        </div>

                        <form action="{{ route('savings.withdrawal.store') }}" method="POST">
                            @csrf

                            <div class="form-grid withdrawal-form-grid">
                                <div class="form-group full-width">
                                    <label for="member_id">
                                        Select Member <span>*</span>
                                    </label>
                                    <div class="withdrawal-select-wrapper">
                                        <i class="bi bi-person-badge"></i>
                                        <select
                                            name="member_id"
                                            id="member_id"
                                            class="@error('member_id') is-invalid @enderror"
                                            required
                                        >
                                            <option value="">Choose a registered member</option>
                                            @foreach ($members as $member)
                                                <option
                                                    value="{{ $member->member_id }}"
                                                    {{ old('member_id', request('member_id')) == $member->member_id ? 'selected' : '' }}
                                                >
                                                    {{ $member->name }} - {{ $member->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('member_id')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group full-width">
                                    <label>Available Balance</label>
                                    <div class="withdrawal-balance-wrapper">
                                        <div class="withdrawal-balance-icon">
                                            <i class="bi bi-piggy-bank"></i>
                                        </div>
                                        <div class="withdrawal-balance-details">
                                            <span>Current savings balance</span>
                                            <strong id="balance">Select a member</strong>
                                        </div>
                                        <div class="withdrawal-balance-status">
                                            <i class="bi bi-shield-check"></i>
                                            <span>Available</span>
                                        </div>
                                    </div>
                                    <p class="withdrawal-helper-text">
                                        The available balance updates automatically when you select a member.
                                    </p>
                                </div>

                                <div class="form-group full-width">
                                    <label for="amount">
                                        Withdrawal Amount <span>*</span>
                                    </label>
                                    <div class="deposit-amount-wrapper withdrawal-amount-wrapper">
                                        <span class="deposit-currency">Rs.</span>
                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            class="@error('amount') is-invalid @enderror"
                                            value="{{ old('amount') }}"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="0.00"
                                            required
                                        >
                                    </div>
                                    @error('amount')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                    <span id="withdrawal-warning" class="field-error withdrawal-warning" hidden></span>
                                    <p class="withdrawal-helper-text">
                                        Enter an amount that does not exceed the available balance.
                                    </p>
                                </div>

                                <div class="form-group full-width">
                                    <label for="remarks">
                                        Remarks <span>*</span>
                                    </label>
                                    <textarea
                                        name="remarks"
                                        id="remarks"
                                        rows="4"
                                        class="@error('remarks') is-invalid @enderror"
                                        placeholder="Enter the reason or additional withdrawal details"
                                        required
                                    >{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                    <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="withdrawal-security-note">
                                <i class="bi bi-info-circle"></i>
                                <span>Please verify the member and withdrawal amount before submitting.</span>
                            </div>

                            <div class="form-actions withdrawal-form-actions">
                                <a href="{{ url()->previous() }}" class="btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn-primary withdrawal-submit-btn">
                                    <i class="bi bi-check-circle"></i>
                                    Save Withdrawal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="member-side-column withdrawal-sidebar">
                    <div class="form-card tips-card withdrawal-tips-card">
                        <div class="tips-title">
                            <span class="withdrawal-tips-icon">
                                <i class="bi bi-lightbulb"></i>
                            </span>
                            Withdrawal Guidelines
                        </div>
                        <ul>
                            <li>
                                <i class="bi bi-check2-circle"></i>
                                <span>Verify the selected member before processing the withdrawal.</span>
                            </li>
                            <li>
                                <i class="bi bi-check2-circle"></i>
                                <span>Check the available savings balance before entering an amount.</span>
                            </li>
                            <li>
                                <i class="bi bi-check2-circle"></i>
                                <span>Ensure the withdrawal amount is within the available balance.</span>
                            </li>
                            <li>
                                <i class="bi bi-check2-circle"></i>
                                <span>Provide a clear reason in the remarks field.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="form-card deposit-note-card withdrawal-note-card">
                        <div class="deposit-note-icon withdrawal-note-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3>Transaction Security</h3>
                        <p>
                            Accurate transaction details help maintain reliable savings records
                            and ensure withdrawals are recorded correctly.
                        </p>
                        <div class="withdrawal-note-footer">
                            <i class="bi bi-check-circle-fill"></i>
                            Verify before saving
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/create_deposit.css')
@endpush

@push('scripts')
    <script>
        const memberSelect = document.getElementById('member_id');
        const balanceDisplay = document.getElementById('balance');
        const amountInput = document.getElementById('amount');
        const withdrawalWarning = document.getElementById('withdrawal-warning');
        const withdrawalForm = amountInput.closest('form');
        const submitButton = withdrawalForm.querySelector('button[type="submit"]');

        const memberBalances = @json(
            $members->mapWithKeys(fn ($member) => [
                $member->member_id => (float) ($member->savingAccount->balance ?? 0)
            ])
        );

        const formatBalance = amount => new Intl.NumberFormat('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);

        const updateBalance = () => {
            const memberId = memberSelect.value;

            if (!memberId) {
                balanceDisplay.textContent = 'Select a member';
                amountInput.removeAttribute('max');
                withdrawalWarning.hidden = true;
                amountInput.setCustomValidity('');
                return;
            }

            const balance = Number(memberBalances[memberId] ?? 0);

            balanceDisplay.textContent = 'Rs. ' + formatBalance(balance);
            amountInput.max = balance;

            validateWithdrawal();
        };

        const validateWithdrawal = () => {
            const memberId = memberSelect.value;
            const amount = Number(amountInput.value);
            const balance = Number(memberBalances[memberId] ?? 0);

            if (memberId && amountInput.value !== '' && amount > balance) {
                withdrawalWarning.textContent = 'Withdrawal amount exceeds the available balance.';
                withdrawalWarning.hidden = false;
                amountInput.setCustomValidity('Withdrawal amount exceeds the available balance.');
            } else {
                withdrawalWarning.hidden = true;
                amountInput.setCustomValidity('');
            }
        };

        memberSelect.addEventListener('change', updateBalance);
        amountInput.addEventListener('input', validateWithdrawal);

        amountInput.addEventListener('wheel', event => {
            event.preventDefault();
        }, { passive: false });

        withdrawalForm.addEventListener('submit', async event => {
            event.preventDefault();

            if (!withdrawalForm.checkValidity()) {
                withdrawalForm.reportValidity();
                return;
            }

            const memberName = memberSelect.options[memberSelect.selectedIndex].text.trim();
            const amount = Number(amountInput.value);

            const result = await Swal.fire({
                title: 'Confirm Withdrawal?',
                html: `
                    <div style="text-align:left;line-height:1.9">
                        <p><strong>Member:</strong> ${memberName.replace(/[&<>"']/g, char => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                })[char])}</p>
                        <p><strong>Withdrawal Amount:</strong> Rs. ${formatBalance(amount)}</p>
                        <p><strong>Available Balance:</strong> Rs. ${formatBalance(Number(memberBalances[memberSelect.value] ?? 0))}</p>
                    </div>
                    <p style="margin-top:12px">Please verify the details before proceeding.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, withdraw',
                cancelButtonText: 'Review details',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    confirmButton: 'savings-swal-confirm',
                    cancelButton: 'savings-swal-cancel'
                },
                buttonsStyling: false
            });

            if (result.isConfirmed) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Processing...';

                HTMLFormElement.prototype.submit.call(withdrawalForm);
            }
        });

        updateBalance();
    </script>
@endpush

