@extends('layouts.app')

@section('title', 'Interest Records')
@section('page-title', 'Interest Records')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Interest Records</h1>
                <p>View previously calculated interest records and generate new interest statements.</p>
            </div>

            <a href="{{ route('savings.get-statement') }}" class="member-add-btn">
                <i class="fa-solid fa-calculator" aria-hidden="true"></i>
                <span style="color:white">Calculate New Interest</span>
            </a>
        </div>

        <div class="members-card">
            <div class="members-table-wrapper">
                <table class="members-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Calculation Period</th>
                        <th>Fiscal Year</th>
                        <th>Total Interest</th>
                        <th>Status</th>
                        <th>Calculated On</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>
                                    {{ $record->startMonth->name }}
                                    {{ $record->startYear->name }}
                                </strong>
                                <span>to</span>
                                <strong>
                                    {{ $record->endMonth->name }}
                                    {{ $record->endYear->name }}
                                </strong>
                            </td>

                            <td>{{ $record->fiscalYear->name }}</td>

                            <td>
                                <strong>{{ number_format($record->total_interest, 2) }}</strong>
                            </td>

                            <td>
                <span class="status-badge {{ $record->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    {{ ucfirst($record->status) }}
                </span>
                            </td>

                            <td>{{ $record->created_at->format('d M Y') }}</td>

                            <td>
                                <a href="{{ route('savings.interest.get-transaction', $record->interest_record_id) }}"
                                   class="btn-primary">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    View Transactions
                                </a>
                                @if($record->status =="active")
                                <a class="btn-danger" onclick="cancelTransaction({{$record->getId()}})">
                                    Cancel Transaction
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-members-content" style="padding: 40px 20px;">
                                    <div class="empty-members-icon">
                                        <i class="fa-solid fa-calculator" aria-hidden="true"></i>
                                    </div>

                                    <strong>No Interest Records Found</strong>

                                    <span>
                        No interest has been calculated yet. Start your first interest calculation.
                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($records, 'links'))
                <div class="members-pagination">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])

@endpush

@push('scripts')

    <script>
            function cancelTransaction(id) {
            Swal.fire({
                title: 'Cancel Transaction?',
                text: 'Are you sure you want to cancel this transaction?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Cancel It',
                cancelButtonText: 'No, Keep It'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/savings/interest/${id}/cancel`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.data.message);
                            }

                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                title: 'Cancelled!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message,
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>

@endpush
