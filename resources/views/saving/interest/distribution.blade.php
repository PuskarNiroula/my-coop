@extends('layouts.app')

@section('title', 'Interest Transactions')
@section('page-title', 'Interest Transactions')

@section('content')
    @php
        $distributionRecords = $records->distributionRecords;

        $totalMembers = count($distributionRecords);
        $totalOpeningBalance = collect($distributionRecords)->sum('openingBalance');
        $totalInterest = collect($distributionRecords)->sum('interest');
        $totalClosingBalance = collect($distributionRecords)->sum('closingBalance');
    @endphp

    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Interest Transactions</h1>
                <p>View member-wise interest distribution details.</p>
            </div>

            <a href="{{ route('savings.interests.index') }}" class="member-add-btn">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span style="color:white">Back to Interest Records</span>
            </a>
        </div>

        <div class="interest-summary-grid">
            <div class="interest-summary-card">
                <div class="interest-summary-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span>Total Members</span>
                    <strong>{{ number_format($totalMembers) }}</strong>
                </div>
            </div>

            <div class="interest-summary-card">
                <div class="interest-summary-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <span>Opening Balance</span>
                    <strong>{{ number_format($totalOpeningBalance, 2) }}</strong>
                </div>
            </div>

            <div class="interest-summary-card">
                <div class="interest-summary-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <span>Total Interest</span>
                    <strong>{{ number_format($totalInterest, 2) }}</strong>
                </div>
            </div>

            <div class="interest-summary-card">
                <div class="interest-summary-icon">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <span>Closing Balance</span>
                    <strong>{{ number_format($totalClosingBalance, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="members-card">
            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Opening Balance</th>
                        <th>Interest Rate</th>
                        <th>Interest Earned</th>
                        <th>Closing Balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($distributionRecords as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $record->member }}</strong>
                            </td>

                            <td>
                                {{ number_format($record->openingBalance, 2) }}
                            </td>

                            <td>
                                    <span class="interest-rate-badge">
                                        {{ number_format($record->rate, 2) }}%
                                    </span>
                            </td>

                            <td>
                                <strong class="interest-earned">
                                    {{ number_format($record->interest, 2) }}
                                </strong>
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($record->closingBalance, 2) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-members-content" style="padding: 40px 20px;">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>

                                    <strong>No Transactions Found</strong>

                                    <span>
                                            There are no interest distribution transactions for this record.
                                        </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                    @if($totalMembers > 0)
                        <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th>{{ number_format($totalOpeningBalance, 2) }}</th>
                            <th>—</th>
                            <th>{{ number_format($totalInterest, 2) }}</th>
                            <th>{{ number_format($totalClosingBalance, 2) }}</th>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])

    <style>
        .interest-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .interest-summary-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }

        .interest-summary-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #ecfdf5;
            color: #198754;
            font-size: 18px;
        }

        .interest-summary-card div:last-child {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 0;
        }

        .interest-summary-card span {
            color: #6b7280;
            font-size: 12px;
        }

        .interest-summary-card strong {
            color: #111827;
            font-size: 18px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .interest-rate-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .interest-earned {
            color: #198754;
        }

        .members-table tfoot {
            background: #f9fafb;
        }

        .members-table tfoot th {
            padding: 16px;
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
        }

        @media (max-width: 1000px) {
            .interest-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 500px) {
            .interest-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
