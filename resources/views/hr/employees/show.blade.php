@extends('layouts.hr')

@section('hr-content')
<div class="container" style="padding: 10px; height: calc(100vh - 100px);">

    <h4 class="mb-2">Human Resource Department</h4>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary mb-3">←Back</a>
    <p>View > {{ $employee->employee_number }} | {{ $employee->last_name }}, {{ $employee->first_name }}</p>

    <!-- Tabs (diretso na dito para sure walang error) -->
    <ul class="nav nav-tabs" id="employeeTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#schedule">Schedules</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#payslip">Payslips</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#deductions">Deductions</a>
        </li>
    </ul>

    <!-- ✅ START of tab-content -->
    <div class="tab-content" id="employeeTabContent">

        {{-- Personal Info --}}
        @include('hr.employees.partials.personal-info')

        {{-- Schedule (kasama modal at scripts) --}}
        @include('hr.employees.partials.schedule')

        {{-- Payslip --}}
        @include('hr.employees.partials.payslip')

    </div>
    <!-- ✅ END of tab-content -->
</div>
@endsection
