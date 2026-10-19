
@extends('layouts.app')

@section('title', 'Import Members')

@section('content')
    @vite(['resources/css/import_member.css'])
    <div class="import-page">
        <div class="import-header">
            <div>
                <h1>Import Members</h1>
                <p>Import multiple members into your cooperative at once.</p>
            </div>
            <a href="{{ asset('format/create_member.xlsx') }}" download="create_member.xlsx" class="btn-download">
                <i class="fa-solid fa-download"></i>
                Download Format
            </a>
        </div>

        <div class="import-card">
            <div class="import-icon">
                <i class="fa-solid fa-file-excel"></i>
            </div>

            <h2>Upload Member Excel File</h2>
            <p>Select your completed Excel template to import member records.</p>

            <form action="/members/import" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="file-upload">
                    <label for="excel_file">Choose Excel File</label>
                    <input
                        type="file"
                        id="excel_file"
                        name="excel_file"
                        accept=".xlsx,.xls,.csv"
                        required
                    >
                    <small>Supported formats: XLSX, XLS, CSV</small>

                    @error('excel_file')
                    <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-import">
                    <i class="fa-solid fa-file-import"></i>
                    Import Members
                </button>
            </form>
        </div>
    </div>
@endsection

