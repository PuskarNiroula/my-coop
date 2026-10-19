@extends('layouts.app')

@section('title', 'Members')

@section('content')
    <livewire:members_table />
@endsection

@push('styles')
    @vite('resources/css/members.css')
@endpush
