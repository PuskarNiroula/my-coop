
@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="page-header">
            <div>
                <h2>Import Saving Accounts</h2>
                <p>Import saving account records using an Excel file.</p>
            </div>
        </div>

        <div class="import-grid">
            <div class="import-card">
                <div class="import-card-header">
                    <div class="import-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div>
                        <h3>Download Format</h3>
                        <p>Download the Excel template before importing records.</p>
                    </div>
                </div>

                <div class="import-card-body">
                    <div class="import-info">
                        <i class="fas fa-info-circle"></i>
                        Fill in the Excel template with saving account details. Keep the column headings unchanged.
                    </div>

                    <a href="{{ route('saving-accounts.import-template') }}" class="btn-import btn-download">
                        <i class="fas fa-download"></i>
                        Download Excel Format
                    </a>
                </div>
            </div>

            <div class="import-card">
                <div class="import-card-header">
                    <div class="import-icon upload-icon">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <div>
                        <h3>Upload Excel File</h3>
                        <p>Select the completed Excel file to import.</p>
                    </div>
                </div>

                <div class="import-card-body">
                    @if ($errors->any())
                        <div class="import-alert import-alert-error">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="import-alert import-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('saving-accounts.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <label for="excel_file" class="form-label">Choose Excel File</label>

                        <div class="file-upload-box" id="fileUploadBox">
                            <input
                                type="file"
                                name="excel_file"
                                id="excel_file"
                                accept=".xlsx,.xls"
                                required
                            >

                            <div class="file-upload-content">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p id="fileName">Click to select an Excel file</p>
                                <span>Accepted formats: .xlsx, .xls</span>
                            </div>
                        </div>

                        @error('excel_file')
                        <span class="field-error">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="btn-import btn-upload">
                            <i class="fas fa-upload"></i>
                            Import Saving Accounts
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .page-content {
            padding: 24px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h2 {
            margin: 0 0 6px;
            color: #243247;
            font-size: 25px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }

        .import-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            align-items: stretch;
        }

        .import-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5eaf0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(31, 45, 61, 0.05);
        }

        .import-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px;
            border-bottom: 1px solid #edf0f4;
        }

        .import-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            color: #16834a;
            background: #e8f7ee;
            border-radius: 10px;
            font-size: 22px;
        }

        .upload-icon {
            color: #2563eb;
            background: #eaf1ff;
        }

        .import-card-header h3 {
            margin: 0 0 5px;
            color: #243247;
            font-size: 17px;
            font-weight: 700;
        }

        .import-card-header p {
            margin: 0;
            color: #718096;
            font-size: 13px;
            line-height: 1.5;
        }

        .import-card-body {
            padding: 22px;
        }

        .import-info {
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
            padding: 14px;
            color: #475569;
            background: #f5f8fc;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.6;
        }

        .import-info i {
            margin-top: 3px;
            color: #2563eb;
        }

        .btn-import {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 45px;
            padding: 12px 16px;
            border: 0;
            border-radius: 7px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-import:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-download {
            background: #16834a;
        }

        .btn-download:hover {
            background: #116b3b;
        }

        .btn-upload {
            margin-top: 20px;
            background: #2563eb;
        }

        .btn-upload:hover {
            background: #1d4ed8;
        }

        .file-upload-box {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 165px;
            padding: 20px;
            border: 2px dashed #cbd5e1;
            border-radius: 9px;
            background: #f8fafc;
            text-align: center;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .file-upload-box:hover {
            border-color: #2563eb;
            background: #f1f6ff;
        }

        .file-upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-content {
            pointer-events: none;
        }

        .file-upload-content i {
            margin-bottom: 12px;
            color: #2563eb;
            font-size: 30px;
        }

        .file-upload-content p {
            margin: 0 0 7px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .file-upload-content span {
            color: #94a3b8;
            font-size: 12px;
        }

        .field-error {
            display: block;
            margin-top: 8px;
            color: #dc2626;
            font-size: 13px;
        }

        .import-alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 7px;
            font-size: 13px;
        }

        .import-alert p {
            margin: 0 0 5px;
        }

        .import-alert p:last-child {
            margin-bottom: 0;
        }

        .import-alert-error {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .import-alert-success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        @media (max-width: 900px) {
            .import-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .page-content {
                padding: 15px;
            }

            .page-header h2 {
                font-size: 21px;
            }

            .import-card-header,
            .import-card-body {
                padding: 17px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('excel_file');
            const fileName = document.getElementById('fileName');

            fileInput?.addEventListener('change', () => {
                const file = fileInput.files[0];

                if (!file) {
                    fileName.textContent = 'Click to select an Excel file';
                    return;
                }

                const extension = file.name.split('.').pop().toLowerCase();

                if (!['xlsx', 'xls'].includes(extension)) {
                    fileInput.value = '';
                    fileName.textContent = 'Invalid file. Select an .xlsx or .xls file.';
                    return;
                }

                fileName.textContent = file.name;
            });
        });
    </script>
@endsection
