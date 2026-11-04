@extends('layouts.app')

@php
$savings = $dto->savings;
$withdrawals = $dto->withdrawals;
$shares = $dto->shares;
$inventory = $dto->inventory;
$incomes = $dto->incomes;
$expenses = $dto->expenses;

@endphp


@section('title', 'Daybook Closing Review')
@section('page-title', 'Daybook Closing Review')

@section('content')
    <section class="page-heading">
        <div>
            <span class="page-eyebrow">DAILY FINANCIAL CONTROL</span>
            <h1>Daybook Closing Review.</h1>
            <p>Review all financial activities and verify today's transactions before closing the daybook.</p>
        </div>

        <div class="closing-date">
            <span>Daybook Date</span>
            <strong>{{ $dayBook->date_nepali ?? now()->format('Y-m-d') }}</strong>
            <small>{{ $dayBook->date_english ?? now()->format('d M Y') }}</small>
        </div>
    </section>

    <section class="closing-review">



        <section class="closing-section">
            <div class="closing-section-heading">
                <div class="closing-section-title">
                    <span class="closing-section-icon">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <div>
                        <h2>Savings & Withdrawals</h2>
                        <p>Review all member savings deposits and withdrawal transactions.</p>
                    </div>
                </div>
            </div>

            <div class="closing-two-column">

                <article class="closing-table-card">
                    <div class="closing-table-heading">
                        <div>
                            <h3>Today's Savings</h3>
                            <p>Member deposit transactions</p>
                        </div>
                        <span class="closing-count">
                            {{ sizeof($savings) }} Transactions
                        </span>
                    </div>

                    <div class="closing-total">
                        <span>Total Deposits</span>
                        <strong>Rs. {{ number_format($totalSavings ?? 0, 2) }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table closing-data-table">
                            <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($savings as $saving)
                                <tr>
                                    <td>
                                        <strong>{{ $saving->memberName ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="amount-positive">
                                        Rs. {{ number_format($saving->amount, 2) }}
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="closing-empty">
                                        No savings transactions recorded today.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="closing-table-card">
                    <div class="closing-table-heading">
                        <div>
                            <h3>Today's Withdrawals</h3>
                            <p>Member withdrawal transactions</p>
                        </div>
                        <span class="closing-count">
                            {{ sizeOf($withdrawals)}} Transactions
                        </span>
                    </div>

                    <div class="closing-total withdrawal-total">
                        <span>Total Withdrawals</span>
                        <strong>Rs. {{ number_format($totalWithdrawals ?? 0, 2) }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table closing-data-table">
                            <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <strong>{{ $withdrawal->memberName ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="amount-negative">
                                        Rs. {{ number_format($withdrawal->amount, 2) }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="closing-empty">
                                        No withdrawals recorded today.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

            </div>
        </section>

        {{-- SHARES --}}

        <section class="closing-section">
            <div class="closing-section-heading">
                <div class="closing-section-title">
                    <span class="closing-section-icon">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    <div>
                        <h2>Share Transactions</h2>
                        <p>Review members who purchased or sold shares today.</p>
                    </div>
                </div>
            </div>

            <article class="closing-table-card">
                <div class="closing-table-heading">
                    <div>
                        <h3>Today's Share Activity</h3>
                        <p>Share purchases and sales recorded today.</p>
                    </div>
                    <span class="closing-count">
                        {{ sizeof($shares) }} Transactions
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="data-table closing-data-table">
                        <thead>
                        <tr>
                            <th>Member</th>
                            <th>Transaction Type</th>
                            <th>Share Quantity</th>
                            <th>Rate</th>
                            <th>Total Amount</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($shares as $share)
                            <tr>
                                <td>
                                    <strong>{{ $share->member->name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                        <span class="closing-status {{ strtolower($share->transaction_type) === 'buy' ? 'status-buy' : 'status-sell' }}">
                                            {{ ucfirst($share->transaction_type) }}
                                        </span>
                                </td>
                                <td>{{ number_format($share->quantity ?? 0) }}</td>
                                <td>Rs. {{ number_format($share->rate ?? 0, 2) }}</td>
                                <td>
                                    <strong>Rs. {{ number_format($share->amount ?? 0, 2) }}</strong>
                                </td>
                                <td>{{ $share->created_at?->format('h:i A') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ $shareEditUrl($share) }}" class="closing-edit-link">
                                        View / Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="closing-empty">
                                    No share transactions recorded today.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        {{-- INVENTORY --}}

        <section class="closing-section">
            <div class="closing-section-heading">
                <div class="closing-section-title">
                    <span class="closing-section-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </span>
                    <div>
                        <h2>Inventory Changes</h2>
                        <p>Review stock additions, deductions and adjustments made today.</p>
                    </div>
                </div>
            </div>

            <article class="closing-table-card">
                <div class="closing-table-heading">
                    <div>
                        <h3>Today's Stock Movements</h3>
                        <p>All inventory changes recorded today.</p>
                    </div>
                    <span class="closing-count">
                        {{ sizeof($inventory) }} Changes
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="data-table closing-data-table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Movement Type</th>
                            <th>Previous Stock</th>
                            <th>Change</th>
                            <th>New Stock</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($inventory as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item->name ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ ucfirst($item->transaction_type ?? 'Adjustment') }}</td>
                                <td>{{ number_format($item->previous_quantity ?? 0) }}</td>
                                <td class="{{ ($item->quantity_change ?? 0) >= 0 ? 'amount-positive' : 'amount-negative' }}">
                                    {{ ($item->quantity_change ?? 0) > 0 ? '+' : '' }}{{ number_format($item->quantity_change ?? 0) }}
                                </td>
                                <td>
                                    <strong>{{ number_format($item->new_quantity ?? 0) }}</strong>
                                </td>
                                <td>{{ $item->created_at?->format('h:i A') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ $inventoryEditUrl($item) }}" class="closing-edit-link">
                                        View / Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="closing-empty">
                                    No inventory changes recorded today.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        {{-- EXPENSES AND INCOME --}}

        <section class="closing-section">
            <div class="closing-section-heading">
                <div class="closing-section-title">
                    <span class="closing-section-icon">
                        <i class="fas fa-money-bill-transfer"></i>
                    </span>
                    <div>
                        <h2>Expenses & Income</h2>
                        <p>Review all cash outflows and inflows recorded today.</p>
                    </div>
                </div>
            </div>

            <div class="closing-two-column">

                <article class="closing-table-card">
                    <div class="closing-table-heading">
                        <div>
                            <h3>Today's Expenses</h3>
                            <p>Payments and operating expenses.</p>
                        </div>
                        <span class="closing-count">
                            {{ sizeof($expenses)}} Transactions
                        </span>
                    </div>

                    <div class="closing-total withdrawal-total">
                        <span>Total Expenses</span>
                        <strong>Rs. {{ number_format($totalExpenses ?? 0, 2) }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table closing-data-table">
                            <thead>
                            <tr>
                                <th>Expense</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>
                                        <strong>{{ $expense->expenseHead->name ?? $expense->description ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="amount-negative">
                                        Rs. {{ number_format($expense->amount ?? 0, 2) }}
                                    </td>
                                    <td>{{ $expense->created_at?->format('h:i A') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ $expenseEditUrl($expense) }}" class="closing-edit-link">
                                            View / Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="closing-empty">
                                        No expenses recorded today.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="closing-table-card">
                    <div class="closing-table-heading">
                        <div>
                            <h3>Today's Income</h3>
                            <p>Income and other receipts.</p>
                        </div>
                        <span class="closing-count">
                            {{ sizeof($incomes) }} Transactions
                        </span>
                    </div>

                    <div class="closing-total">
                        <span>Total Income</span>
                        <strong>Rs. {{ number_format($totalIncome ?? 0, 2) }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table closing-data-table">
                            <thead>
                            <tr>
                                <th>Income</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($incomes as $income)
                                <tr>
                                    <td>
                                        <strong>{{ $income->incomeHead->name ?? $income->description ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="amount-positive">
                                        Rs. {{ number_format($income->amount ?? 0, 2) }}
                                    </td>
                                    <td>{{ $income->created_at?->format('h:i A') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ $incomeEditUrl($income) }}" class="closing-edit-link">
                                            View / Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="closing-empty">
                                        No income recorded today.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

            </div>
        </section>

        {{-- FINAL CLOSING --}}

        <section class="closing-confirmation">
            <div class="closing-confirmation-content">
                <span class="closing-confirmation-icon">
                    <i class="fas fa-shield-halved"></i>
                </span>
                <div>
                    <h2>Ready to close the daybook?</h2>
                    <p>
                        Verify all transactions above. If you find a mistake, use the
                        View / Edit action to correct it before closing.
                    </p>
                </div>
            </div>

            <form action="{{ route('daybook.close',$dayBook->getId()) }}" method="POST" id="closeDaybookForm">
                @csrf

                <button type="button" class="btn-primary" id="confirmCloseButton">
                    <i class="fas fa-lock"></i>
                    Confirm & Close Daybook
                </button>
            </form>
        </section>

    </section>

    <div class="closing-modal-backdrop" id="closingModal">
        <div class="closing-modal">
            <span class="closing-modal-icon">
                <i class="fas fa-lock"></i>
            </span>

            <h2>Confirm Daybook Closing</h2>
            <p>
                Are you sure you have reviewed all today's transactions?
                Once closed, further changes should require an authorized reopening.
            </p>

            <div class="closing-modal-actions">
                <button type="button" class="btn-secondary" id="cancelClosing">
                    Go Back
                </button>

                <button type="button" class="btn-primary" id="finalClose">
                    Yes, Close Daybook
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .closing-review {
            display: flex;
            flex-direction: column;
            gap: 27px;
        }

        .closing-date {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 150px;
            padding: 13px 17px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .closing-date span,
        .closing-date small {
            color: var(--muted);
            font-size: 10px;
        }

        .closing-date strong {
            color: var(--forest);
            font-size: 15px;
        }

        .closing-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .closing-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .closing-section-title {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .closing-section-icon {
            display: grid;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            place-items: center;
            color: var(--green);
            border: 1px solid #e1eade;
            border-radius: 10px;
            background: #eff5ed;
            font-size: 17px;
        }

        .closing-section-title h2 {
            margin: 0 0 5px;
            color: var(--forest);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 21px;
            font-weight: 600;
        }

        .closing-section-title p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .closing-two-column {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 17px;
        }

        .closing-table-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fff;
            box-shadow: 0 3px 10px rgba(23, 48, 30, .025);
        }

        .closing-table-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            padding: 19px 20px;
            border-bottom: 1px solid #edf0e9;
        }

        .closing-table-heading h3 {
            margin: 0 0 5px;
            color: var(--forest);
            font-size: 13px;
            font-weight: 700;
        }

        .closing-table-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 10px;
        }

        .closing-count {
            padding: 6px 9px;
            color: var(--green);
            border: 1px solid #e0e9dc;
            border-radius: 20px;
            background: #f3f7f0;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .closing-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 13px 20px;
            border-bottom: 1px solid #edf0e9;
            background: #f5f8f2;
        }

        .closing-total span {
            color: #65766a;
            font-size: 11px;
            font-weight: 600;
        }

        .closing-total strong {
            color: var(--forest);
            font-size: 15px;
            font-weight: 700;
        }

        .withdrawal-total {
            background: #fff5f4;
        }

        .withdrawal-total span,
        .withdrawal-total strong {
            color: #a43c42;
        }

        .closing-data-table {
            width: 100%;
            min-width: 530px;
            border-collapse: collapse;
        }

        .closing-data-table thead th {
            padding: 12px 13px;
            color: #758177;
            border-bottom: 1px solid #edf0e9;
            background: #fcfcf9;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .3px;
            text-align: left;
            white-space: nowrap;
        }

        .closing-data-table tbody td {
            padding: 13px;
            color: #526055;
            border-bottom: 1px solid #f0f1eb;
            font-size: 10px;
            white-space: nowrap;
        }

        .closing-data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .closing-data-table tbody tr:hover {
            background: #fbfcf8;
        }

        .closing-data-table td strong {
            color: #304638;
            font-weight: 700;
        }

        .amount-positive {
            color: #237449 !important;
            font-weight: 700;
        }

        .amount-negative {
            color: #d04450 !important;
            font-weight: 700;
        }

        .closing-edit-link {
            color: var(--green);
            font-size: 10px;
            font-weight: 700;
            text-decoration: underline;
            text-decoration-color: #c6d7c7;
            text-underline-offset: 3px;
        }

        .closing-edit-link:hover {
            color: var(--gold);
        }

        .closing-empty {
            padding: 28px 12px !important;
            color: #9aa297 !important;
            text-align: center;
            white-space: normal !important;
        }

        .closing-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .status-buy {
            color: #237449;
            background: #eaf5ec;
        }

        .status-sell {
            color: #c03945;
            background: #fff0ef;
        }

        .closing-confirmation {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            padding: 24px;
            border: 1px solid rgba(201, 168, 106, .4);
            border-radius: 12px;
            background:
                radial-gradient(ellipse at 100% 0%, rgba(201, 168, 106, .10), transparent 45%),
                #fffefa;
        }

        .closing-confirmation-content {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .closing-confirmation-icon {
            display: grid;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            place-items: center;
            color: #a98236;
            border: 1px solid #e9dcc0;
            border-radius: 50%;
            background: #faf4e8;
            font-size: 18px;
        }

        .closing-confirmation h2 {
            margin: 0 0 7px;
            color: var(--forest);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 19px;
            font-weight: 600;
        }

        .closing-confirmation p {
            max-width: 540px;
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .closing-modal-backdrop {
            position: fixed;
            z-index: 999;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(13, 43, 30, .58);
            backdrop-filter: blur(4px);
        }

        .closing-modal-backdrop.is-visible {
            display: flex;
        }

        .closing-modal {
            width: 100%;
            max-width: 430px;
            padding: 30px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fffefa;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .16);
            text-align: center;
        }

        .closing-modal-icon {
            display: grid;
            width: 55px;
            height: 55px;
            margin: 0 auto 18px;
            place-items: center;
            color: var(--gold);
            border: 1px solid #eadfc8;
            border-radius: 50%;
            background: #faf4e8;
            font-size: 21px;
        }

        .closing-modal h2 {
            margin: 0 0 10px;
            color: var(--forest);
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 24px;
        }

        .closing-modal p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .closing-modal-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 25px;
        }

        @media (max-width: 1050px) {
            .closing-two-column {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .closing-date {
                width: 100%;
            }

            .closing-section-title h2 {
                font-size: 18px;
            }

            .closing-table-heading {
                padding: 16px 13px;
            }

            .closing-total {
                padding: 12px 13px;
            }

            .closing-confirmation {
                align-items: stretch;
                padding: 19px 15px;
            }

            .closing-confirmation-content {
                align-items: flex-start;
            }

            .closing-confirmation .btn-primary {
                width: 100%;
            }

            .closing-modal {
                padding: 23px 17px;
            }

            .closing-modal-actions {
                flex-direction: column-reverse;
            }

            .closing-modal-actions button {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('closingModal');
            const openButton = document.getElementById('confirmCloseButton');
            const cancelButton = document.getElementById('cancelClosing');
            const finalButton = document.getElementById('finalClose');
            const form = document.getElementById('closeDaybookForm');

            openButton.addEventListener('click', function () {
                modal.classList.add('is-visible');
            });

            cancelButton.addEventListener('click', function () {
                modal.classList.remove('is-visible');
            });

            finalButton.addEventListener('click', function () {
                form.submit();
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.remove('is-visible');
                }
            });
        });
    </script>
@endpush
