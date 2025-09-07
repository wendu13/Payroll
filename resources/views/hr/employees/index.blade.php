@extends('layouts.hr')

@section('hr-content')

<div class="container">

    {{-- Fixed Top Bar --}}
    <div class="fixed-top-bar" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Employee Management</h2>
        <form method="GET" action="{{ route('employees.index') }}" id="filterForm" style="display: flex; gap: 10px;">
            {{-- Search Input --}}
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="form-control" style="width: 230px;" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
        </form>
    </div>

    {{-- Dropdown + Buttons --}}
    <div class="dropdown-button-row" style="display: flex; justify-content: space-between; align-items: center;">
        <select class="form-control" name="department" style="width: 250px;" onchange="this.form.submit()">
            <option value="">All Departments</option>
            @foreach($departments as $department)
                <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                    {{ $department }}
                </option>
            @endforeach
        </select>
        <div>
            <button class="btn btn-secondary">Add Department</button>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
        </div>
    </div>

    {{-- Department Title --}}
    <h5>
        {{ isset($selectedDepartment) ? $selectedDepartment->name : 'All Employees' }}
    </h5>

    {{-- Employee Table --}}
    <div class="table-container">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Number</th>
                    <th>Name</th>
                    <th>Bank Account</th>
                    <th>Contact #</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee->employee_number }}</td>
                        <td>{{ $employee->last_name }}, {{ $employee->first_name }}</td>
                        <td>{{ $employee->bank_account }}</td>
                        <td>{{ $employee->contact }}</td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
