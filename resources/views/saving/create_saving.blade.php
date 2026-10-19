@extends('layouts.app')

@section('title', 'Create Saving Account')
@section('page-title', 'Create Saving Account')

@section('content')
    <livewire:create-savings-accounts />
@endsection

@push('styles')
    @vite(['resources/css/create_saving.css'])
@endpush

