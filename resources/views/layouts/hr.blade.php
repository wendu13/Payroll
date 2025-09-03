{{-- resources/views/layouts/hr.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="p-4">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        @yield('hr-content') {{-- Placeholder for HR pages --}}
    </div>
@endsection
