<div class="savings-create-page">
    <div class="savings-create-header">
        <div>
            <span class="savings-create-eyebrow">ACCOUNT MANAGEMENT</span>
            <h1>Create Savings Accounts</h1>
            <p>Open savings accounts for eligible members.</p>
        </div>

        <a href="{{ url('/savings') }}" class="savings-back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Savings
        </a>
    </div>

    @if (session()->has('success'))
        <div class="savings-success-message">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="savings-create-card">
        <div class="savings-card-heading">
            <div class="savings-heading-icon">
                <i class="fa-solid fa-piggy-bank"></i>
            </div>
            <div>
                <h2>New Account Details</h2>
                <p>Select a member and enter their opening balance.</p>
            </div>
        </div>

        <div class="savings-table-responsive">
            <table class="savings-create-table">
                <thead>
                <tr>
                    <th class="savings-col-number">#</th>
                    <th>Member</th>
                    <th>Opening Balance</th>
                    <th class="savings-col-action">Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($rows as $index => $row)
                    <tr wire:key="saving-row-{{ $index }}">
                        <td class="savings-row-number">
                            {{ $index + 1 }}
                        </td>

                        <td>

                            <select
                                class="savings-form-control"
                                wire:model.live="rows.{{ $index }}.member_id"
                            >
                                <option value="">Select a member</option>

                                @foreach ($eligibleMembers as $member)
                                    @if (!collect($rows)->contains(
                                        fn ($otherRow, $otherIndex) =>
                                            $otherIndex !== $index &&
                                            (string) ($otherRow['member_id'] ?? '') === (string) $member->member_id
                                    ))
                                        <option value="{{ $member->member_id }}">
                                            {{ $member->name }}{{ $member->phone ? ' — ' . $member->phone : '' }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>


                            @error("rows.$index.member_id")
                            <span class="savings-field-error">{{ $message }}</span>
                            @enderror
                        </td>

                        <td>
                            <div class="savings-amount-input">
                                <span>Rs.</span>
                                <input
                                    type="number"
                                    class="savings-form-control"
                                    wire:model="rows.{{ $index }}.balance"
                                    min="0"
                                    max="999999999999.99"
                                    step="0.01"
                                    placeholder="0.00"
                                >
                            </div>

                            @error("rows.$index.balance")
                            <span class="savings-field-error">{{ $message }}</span>
                            @enderror
                        </td>

                        <td class="savings-row-actions">
                            @if (count($rows) > 1)
                                <button
                                    type="button"
                                    class="savings-remove-btn"
                                    wire:click="removeRow({{ $index }})"
                                    title="Remove row"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @else
                                <span class="savings-action-placeholder">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @error('rows')
        <div class="savings-form-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $message }}
        </div>
        @enderror

        @if ($eligibleMembers->isEmpty())
            <div class="savings-no-members">
                <i class="fa-solid fa-users"></i>
                <strong>No eligible members found</strong>
                <span>All active members may already have a savings account.</span>
            </div>
        @endif

        <div class="savings-add-row-section">
            <button
                type="button"
                class="savings-add-row-btn"
                wire:click="addRow"
                wire:loading.attr="disabled"
                wire:target="addRow"
            >
                <i class="fa-solid fa-plus"></i>
                Add More
            </button>
            <span class="savings-row-count">
                {{ count($rows) }} {{ count($rows) === 1 ? 'row' : 'rows' }}
            </span>
        </div>

        <div class="savings-create-footer">
            <div class="savings-total">
                <span>Total Opening Balance</span>
                <strong>
                    Rs. {{ number_format(collect($rows)->sum(fn ($row) => is_numeric($row['balance'] ?? null) ? (float) $row['balance'] : 0), 2) }}
                </strong>
            </div>

            <button
                type="button"
                class="savings-submit-btn"
                wire:click="$dispatch('confirm-create-savings-accounts')"
                wire:loading.attr="disabled"
                wire:target="createAccounts"
                @disabled($eligibleMembers->isEmpty())
            >
                <span wire:loading.remove wire:target="createAccounts">
                    <i class="fa-solid fa-check"></i>
                    Create Accounts
                </span>
                <span wire:loading wire:target="createAccounts">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Creating...
                </span>
            </button>
        </div>
    </div>
</div>

@script
<script>
    window.addEventListener('confirm-create-savings-accounts', async () => {
        const result = await Swal.fire({
            title: 'Create savings accounts?',
            text: 'Please verify the selected members and opening balances before continuing.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, create accounts',
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
            await $wire.createAccounts();
        }
    });

    $wire.on('savings-accounts-created', () => {
        Swal.fire({
            title: 'Accounts Created',
            text: 'The savings accounts were created successfully.',
            icon: 'success',
            confirmButtonText: 'Done',
            customClass: {
                confirmButton: 'savings-swal-confirm'
            },
            buttonsStyling: false
        });
    });
</script>
@endscript
