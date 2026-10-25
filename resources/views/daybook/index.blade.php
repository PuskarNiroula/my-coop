@extends('layouts.app')

@section('title', 'Day Book')
@section('page-title', 'Day Book')

@section('content')
    <livewire:day-books-table />
@endsection

@push('styles')
    @vite(['resources/css/members.css'])
@endpush
