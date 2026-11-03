@extends('layouts.app')

@section('title', 'Expense Report')
@section('page-title', 'Expense Report')

@section('content')

    @php
        $monthNames = $months->flip();
        $yearNames = $years->flip();

        $periodParts = [];

        if (request('month')) {
            $periodParts[] = $monthNames->get((int) request('month'));
        }

        if (request('year')) {
            $periodParts[] = $yearNames->get((int) request('year'));
        }

        $periodLabel = trim(implode(' ', array_filter($periodParts)));

        if (request('fiscal_year')) {
            $periodLabel = trim($periodLabel . ' · Fiscal Year ' . $fiscalYearName, ' ·');
        }
    @endphp

    <div class="er-page">

        <div class="er-heading">
            <div>
                <h1>Expense Report</h1>
                <p>Filter, review, and print your organization's expense records.</p>
            </div>

            <button type="button" class="er-btn er-btn-outline" onclick="printExpenseReport()">
                <i class="fa-solid fa-print"></i>
                <span>Print Report</span>
            </button>
        </div>

        <form action="{{ route('expense.records.reports') }}" method="GET" class="er-filter">
            <div class="er-field">
                <label for="month">Month</label>
                <select name="month" id="month">
                    <option value="">All Months</option>

                    @foreach($months as $name => $monthId)
                        <option value="{{ $monthId }}" {{ request('month') == $monthId ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="er-field">
                <label for="year">Year</label>
                <select name="year" id="year">
                    <option value="">All Years</option>

                    @foreach($years as $yearName => $yearId)
                        <option value="{{ $yearId }}" {{ request('year') == $yearId ? 'selected' : '' }}>
                            {{ $yearName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="er-field">
                <label for="fiscal_year">Fiscal Year</label>
                <select name="fiscal_year" id="fiscal_year">
                    <option value="">All Fiscal Years</option>

                    @foreach($fiscalYears as $id=>$name)
                        <option value="{{ $id }}" {{ request('fiscal_year') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="er-filter-actions">
                <button type="submit" class="er-btn er-btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Generate</span>
                </button>

                <a href="{{ route('expense.records.reports') }}" class="er-btn er-btn-ghost">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>

        <div class="er-card" id="expenseReport">

            <div class="er-report-head">
                <div>
                    <h2>Expense Report</h2>
                    <p>{{ $periodLabel !== '' ? $periodLabel : 'All periods' }}</p>
                </div>

                <div class="er-report-meta">
                    <span>{{ $expenseRecords->count() }} {{ \Illuminate\Support\Str::plural('record', $expenseRecords->count()) }}</span>
                </div>
            </div>

            <div class="er-table-wrap">
                <table class="er-table">
                    <thead>
                    <tr>
                        <th class="er-col-sn">SN</th>
                        <th>Expense Head</th>
                        <th>Description</th>
                        <th class="er-col-amount">Amount</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($expenseRecords as $record)
                        <tr>
                            <td class="er-col-sn">{{ $loop->iteration }}</td>
                            <td class="er-head-name">{{ $record->expenseHead->name }}</td>
                            <td class="er-description">{{ $record->description ?: '-' }}</td>
                            <td class="er-col-amount">{{ number_format($record->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="er-empty">
                                <div class="er-empty-icon">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <strong>No expense records found</strong>
                                <span>Try a different period or reset the filters.</span>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                    @if($expenseRecords->count())
                        <tfoot>
                        <tr>
                            <td colspan="3" class="er-total-label">Total Expense</td>
                            <td class="er-col-amount er-total-value">{{ number_format($expenseRecords->sum('amount'), 2) }}</td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

        </div>

    </div>

@endsection

@push('styles')
   <style>
       .er-page {
           max-width: 1500px;
           margin: 0 auto;
           padding: 28px 24px 40px;
           color: #263b30;
       }

       .er-heading {
           display: flex;
           align-items: center;
           justify-content: space-between;
           gap: 16px;
           margin-bottom: 20px;
       }

       .er-heading h1 {
           margin: 0 0 7px;
           color: #174d35;
           font-size: 28px;
           font-weight: 700;
           letter-spacing: -0.01em;
       }

       .er-heading p {
           margin: 0;
           color: #78877d;
           font-size: 14px;
       }

       .er-btn {
           display: inline-flex;
           align-items: center;
           justify-content: center;
           gap: 8px;
           height: 42px;
           padding: 0 18px;
           border: 1px solid transparent;
           border-radius: 8px;
           font-family: inherit;
           font-size: 14px;
           font-weight: 600;
           line-height: 1;
           text-decoration: none;
           white-space: nowrap;
           cursor: pointer;
           transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
       }

       .er-btn:focus-visible {
           outline: 3px solid rgba(67, 132, 94, 0.35);
           outline-offset: 2px;
       }

       .er-btn-primary {
           border-color: #176640;
           background: #176640;
           color: #fff;
       }

       .er-btn-primary:hover {
           border-color: #105331;
           background: #105331;
           color: #fff;
       }

       .er-btn-outline {
           border-color: #176640;
           background: #fff;
           color: #176640;
       }

       .er-btn-outline:hover {
           background: #176640;
           color: #fff;
       }

       .er-btn-ghost {
           border-color: #dce5de;
           background: #fff;
           color: #52665a;
       }

       .er-btn-ghost:hover {
           border-color: #c5d3c9;
           background: #f5f8f5;
           color: #174d35;
       }

       .er-filter {
           display: grid;
           grid-template-columns: repeat(3, minmax(0, 1fr)) auto;
           align-items: end;
           gap: 16px;
           margin-bottom: 20px;
           padding: 18px 20px;
           border: 1px solid #e4ebe5;
           border-radius: 12px;
           background: #fff;
           box-shadow: 0 5px 20px rgba(23, 77, 53, 0.05);
       }

       .er-field {
           display: flex;
           flex-direction: column;
           gap: 7px;
           min-width: 0;
       }

       .er-field label {
           color: #52665a;
           font-size: 12px;
           font-weight: 700;
       }

       .er-field select,
       .er-field input {
           box-sizing: border-box;
           width: 100%;
           height: 42px;
           padding: 0 12px;
           border: 1px solid #dce5de;
           border-radius: 8px;
           background: #fbfdfb;
           color: #263b30;
           font-family: inherit;
           font-size: 13px;
           transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
       }

       .er-field input::placeholder {
           color: #9aa69e;
       }

       .er-field select:focus,
       .er-field input:focus {
           outline: none;
           border-color: #43845e;
           background: #fff;
           box-shadow: 0 0 0 3px rgba(67, 132, 94, 0.12);
       }

       .er-filter-actions {
           display: flex;
           align-items: center;
           gap: 10px;
       }

       .er-card {
           overflow: hidden;
           border: 1px solid #e4ebe5;
           border-radius: 12px;
           background: #fff;
           box-shadow: 0 5px 20px rgba(23, 77, 53, 0.05);
       }

       .er-report-head {
           display: flex;
           align-items: center;
           justify-content: space-between;
           gap: 16px;
           padding: 20px 22px;
           border-bottom: 1px solid #edf1ed;
           border-left: 4px solid #176640;
       }

       .er-report-head h2 {
           margin: 0 0 4px;
           color: #174d35;
           font-size: 18px;
           font-weight: 700;
       }

       .er-report-head p {
           margin: 0;
           color: #728176;
           font-size: 13px;
       }

       .er-report-meta {
           display: flex;
           flex-direction: column;
           align-items: flex-end;
           gap: 3px;
           color: #829087;
           font-size: 12px;
       }

       .er-report-meta span:first-child {
           color: #365642;
           font-weight: 600;
       }

       .er-table-wrap {
           width: 100%;
           overflow-x: auto;
       }

       .er-table {
           width: 100%;
           border-collapse: collapse;
           text-align: left;
       }

       .er-table thead th {
           padding: 14px 22px;
           border-bottom: 1px solid #e4ebe5;
           background: #f5f8f5;
           color: #52665a;
           font-size: 12px;
           font-weight: 700;
           white-space: nowrap;
       }

       .er-table tbody td {
           padding: 15px 22px;
           border-bottom: 1px solid #edf1ed;
           color: #59685e;
           font-size: 13px;
           vertical-align: middle;
       }

       .er-table tbody tr {
           transition: background 0.15s ease;
       }

       .er-table tbody tr:hover {
           background: #f6faf6;
       }

       .er-table tbody tr:last-child td {
           border-bottom: none;
       }

       .er-col-sn {
           width: 70px;
           color: #8a978f;
           font-variant-numeric: tabular-nums;
       }

       .er-col-amount {
           width: 160px;
           text-align: right !important;
           font-variant-numeric: tabular-nums;
           white-space: nowrap;
       }

       .er-head-name {
           color: #234c35 !important;
           font-size: 14px !important;
           font-weight: 600;
           white-space: nowrap;
       }

       .er-description {
           min-width: 220px;
           line-height: 1.5;
       }

       .er-table tbody .er-col-amount {
           color: #234c35;
           font-weight: 600;
       }

       .er-table tfoot td {
           padding: 16px 22px;
           border-top: 2px solid #d5e3d9;
           background: #f5f8f5;
           color: #174d35;
           font-size: 14px;
           font-weight: 700;
       }

       .er-total-label {
           text-align: right;
       }

       .er-total-value {
           font-size: 15px !important;
       }

       .er-empty {
           padding: 56px 20px !important;
           text-align: center;
       }

       .er-empty-icon {
           display: inline-flex;
           align-items: center;
           justify-content: center;
           width: 56px;
           height: 56px;
           margin-bottom: 12px;
           border-radius: 50%;
           background: #eaf4ec;
           color: #5f9a77;
           font-size: 22px;
       }

       .er-empty strong,
       .er-empty span {
           display: block;
       }

       .er-empty strong {
           margin-bottom: 5px;
           color: #365642;
           font-size: 15px;
       }

       .er-empty span {
           color: #829087;
           font-size: 13px;
       }

       @media (max-width: 1000px) {
           .er-filter {
               grid-template-columns: repeat(3, minmax(0, 1fr));
           }

           .er-filter-actions {
               grid-column: 1 / -1;
               justify-content: flex-end;
           }
       }

       @media (max-width: 700px) {
           .er-page {
               padding: 14px;
           }

           .er-heading {
               align-items: flex-start;
               flex-direction: column;
           }

           .er-heading h1 {
               font-size: 24px;
           }

           .er-heading .er-btn {
               width: 100%;
           }

           .er-filter {
               grid-template-columns: 1fr;
               padding: 16px;
           }

           .er-filter-actions .er-btn {
               flex: 1;
           }

           .er-report-head {
               align-items: flex-start;
               flex-direction: column;
           }

           .er-report-meta {
               align-items: flex-start;
           }

           .er-table thead th,
           .er-table tbody td,
           .er-table tfoot td {
               padding: 13px 14px;
           }
       }

       @media print {
           @page {
               size: A4 portrait;
               margin: 15mm;
           }

           html,
           body {
               width: 100%;
               margin: 0 !important;
               padding: 0 !important;
               background: #fff !important;
           }

           body * {
               visibility: hidden !important;
           }

           #expenseReport,
           #expenseReport * {
               visibility: visible !important;
           }

           #expenseReport {
               position: absolute !important;
               top: 0 !important;
               left: 0 !important;
               width: 100% !important;
               margin: 0 !important;
               padding: 0 !important;
               border: none !important;
               border-radius: 0 !important;
               box-shadow: none !important;
               overflow: visible !important;
           }

           #expenseReport .er-report-head {
               padding: 0 0 15px !important;
               border-left: none !important;
               border-bottom: 2px solid #000 !important;
           }

           #expenseReport .er-table-wrap {
               width: 100% !important;
               overflow: visible !important;
           }

           #expenseReport .er-table {
               width: 100% !important;
               border-collapse: collapse !important;
               page-break-inside: auto;
           }

           #expenseReport .er-table thead {
               display: table-header-group;
           }

           #expenseReport .er-table tfoot {
               display: table-footer-group;
           }

           #expenseReport .er-table tr {
               page-break-inside: avoid;
           }

           #expenseReport .er-table th,
           #expenseReport .er-table td {
               padding: 10px !important;
               border: 1px solid #999 !important;
               color: #000 !important;
               background: #fff !important;
               font-size: 12px !important;
           }

           #expenseReport .er-table thead th {
               background: #eee !important;
               font-weight: bold !important;
           }

           #expenseReport .er-table tfoot td {
               background: #eee !important;
               font-weight: bold !important;
           }

           #expenseReport .er-report-head h2,
           #expenseReport .er-report-head p,
           #expenseReport .er-report-meta {
               color: #000 !important;
           }
       }

       @media (prefers-reduced-motion: reduce) {
           .er-btn,
           .er-table tbody tr,
           .er-field select,
           .er-field input {
               transition: none;
           }
       }
   </style>
@endpush

@push('scripts')
    <script>
        function printExpenseReport() {
            Swal.fire({
                title: 'Print Expense Report?',
                text: 'Do you want to print this expense report?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#176640',
                cancelButtonColor: '#6c757d'
            }).then(result => {
                if (result.isConfirmed) {
                    setTimeout(() => {
                        window.print();
                    }, 300);
                }
            });
        }
    </script>
@endpush
