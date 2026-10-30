@extends('layouts.app')

@section('title', 'Calculate Interest')
@section('page-title', 'Calculate Interest')

@section('content')
    <div class="members-page">
        <div class="members-heading">
            <div>
                <h1>Calculate Interest</h1>
                <p>Calculate member interest for the selected month.</p>
            </div>
        </div>

        <div class="members-card">
            <div class="members-table-wrapper" style="padding: 48px 24px;">
                <div class="empty-members-content">
                    <div class="empty-members-icon">
                        <i class="fa-solid fa-calculator" aria-hidden="true"></i>
                    </div>

                    <strong>Calculate Interest upto {{ $month }} - {{$yearName}}</strong>

                    <span>
                        This process may take some time. Please ensure you want to proceed before starting the calculation.
                    </span>

                    <form
                        action="{{route('savings.get-statement')}}"
                        method="POST"
                        id="calculate-interest-form"
                    >
                        @csrf

                        <button type="submit" class="member-add-btn">
                            <i class="fa-solid fa-calculator" aria-hidden="true"></i>
                            <span style="color:white">Generate Semi Annual Statement</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/member-table.css'])
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('calculate-interest-form');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Generate Statement?',
                    text: 'This is a resource-intensive process. Are you sure you want to calculate interest for {{ $month }}?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Calculate Interest',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
