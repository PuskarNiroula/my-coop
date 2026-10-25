@extends('layouts.app')

@section('title', 'Savings Accounts')
@section('page-title', 'Savings Accounts')

@section('content')
    <livewire:saving-accounts-table />
@endsection

@push('styles')
    @vite(['resources/css/members.css'])
@endpush
