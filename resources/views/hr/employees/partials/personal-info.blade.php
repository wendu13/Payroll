<!-- Personal Info Tab -->
<div class="tab-pane fade show active" id="personal" role="tabpanel">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Personal Details</div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Employee Number:</strong> {{ $employee->employee_id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Position:</strong> {{ $employee->position }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Last Name:</strong> {{ $employee->last_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>First Name:</strong> {{ $employee->first_name }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Middle Name:</strong> {{ $employee->middle_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>ZIP Code:</strong> {{ $employee->zip_code }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Address:</strong> {{ $employee->address }}
                        </div>
                        <div class="col-md-6">
                            <strong>Contact:</strong> {{ $employee->contact }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $employee->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $employee->phone }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Birthplace:</strong> {{ $employee->birthplace }}
                        </div>
                        <div class="col-md-6">
                            <strong>Birthdate:</strong> {{ $employee->birthdate }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Age:</strong> {{ $employee->age }}
                        </div>
                        <div class="col-md-6">
                            <strong>Sex:</strong> {{ $employee->gender }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Civil Status:</strong> {{ $employee->civil_status }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nationality:</strong> {{ $employee->nationality }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>PAG-IBIG No.:</strong> {{ $employee->pagibig }}
                        </div>
                        <div class="col-md-6">
                            <strong>SSS No.:</strong> {{ $employee->sss }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>PhilHealth No.:</strong> {{ $employee->philhealth }}
                        </div>
                        <div class="col-md-6">
                            <strong>TIN No.:</strong> {{ $employee->tin }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Bank Account:</strong> {{ $employee->bank_account }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Photo) -->
        <div class="col-md-4 text-center">
            @if ($employee->photo)
                <img src="{{ asset('storage/' . $employee->photo) }}" 
                     alt="Profile Photo" 
                     style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
            @else
                <p>No photo uploaded</p>
            @endif

            <div class="mt-2">
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
