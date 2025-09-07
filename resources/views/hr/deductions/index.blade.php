@extends('layouts.hr')

@section('hr-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Deduction Types</h4>
        <a href="{{ route('deductions.create') }}" class="btn btn-success">
            <i data-lucide="plus"></i> Add Deduction
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deductionTypes as $deduction)
            <tr>
                <td>{{ $deduction->name }}</td>
                <td>{{ ucfirst($deduction->type) }}</td>
                <td>{{ $deduction->is_active ? 'Active' : 'Inactive' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No deductions yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
