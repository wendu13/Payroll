@extends('layouts.hr')

@section('hr-content')
<div class="container" style="padding: 10px; height: calc(100vh - 100px);">

    <h4 class="mb-2">Human Resource Department</h4>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary mb-3">←Back</a>
    <p>View > {{ $employee->employee_number }} | {{ $employee->last_name }}, {{ $employee->first_name }}</p>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="employeeTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">Personal Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">Schedule</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payslip-tab" data-bs-toggle="tab" data-bs-target="#payslip" type="button" role="tab">Pay Slip</button>
        </li>
    </ul>

    <!-- ✅ START of tab-content -->
    <div class="tab-content" id="employeeTabContent">

        <!-- Personal Info Tab -->
        <div class="tab-pane fade show active" id="personal" role="tabpanel">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">Personal Details</div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Employee Number:</strong> {{ $employee->employee_id }}</div>
                                <div class="col-md-6"><strong>Position:</strong> {{ $employee->position }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Last Name:</strong> {{ $employee->last_name }}</div>
                                <div class="col-md-6"><strong>First Name:</strong> {{ $employee->first_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Middle Name:</strong> {{ $employee->middle_name }}</div>
                                <div class="col-md-6"><strong>ZIP Code:</strong> {{ $employee->zip_code }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Address:</strong> {{ $employee->address }}</div>
                                <div class="col-md-6"><strong>Contact:</strong> {{ $employee->contact }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Email:</strong> {{ $employee->email }}</div>
                                <div class="col-md-6"><strong>Phone:</strong> {{ $employee->phone }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Birthplace:</strong> {{ $employee->birthplace }}</div>
                                <div class="col-md-6"><strong>Birthdate:</strong> {{ $employee->birthdate }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Age:</strong> {{ $employee->age }}</div>
                                <div class="col-md-6"><strong>Sex:</strong> {{ $employee->gender }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Civil Status:</strong> {{ $employee->civil_status }}</div>
                                <div class="col-md-6"><strong>Nationality:</strong> {{ $employee->nationality }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>PAG-IBIG No.:</strong> {{ $employee->pagibig }}</div>
                                <div class="col-md-6"><strong>SSS No.:</strong> {{ $employee->sss }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>PhilHealth No.:</strong> {{ $employee->philhealth }}</div>
                                <div class="col-md-6"><strong>TIN No.:</strong> {{ $employee->tin }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Bank Account:</strong> {{ $employee->bank_account }}</div>
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
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">Edit</a>
                    </div>
                </div>
            </div>
        </div>

<!-- Schedule Tab -->
<div class="tab-pane fade" id="schedule" role="tabpanel">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Employee Schedule</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                + Add Schedule
            </button>
        </div>

        <!-- 👉 Eto na yung pinalitan -->
        <div class="card-body">
            @if($employee->schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->schedules as $schedule)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($schedule->date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $schedule->type === 'working' ? 'primary' : 'danger' }}">
                                        {{ ucfirst($schedule->type) }}
                                    </span>
                                </td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>
                                    <a href="{{ route('schedule.view',$schedule->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('schedule.print',$schedule->id) }}" target="_blank" class="btn btn-sm btn-secondary">Print</a>
                                    <a href="{{ route('schedule.download',$schedule->id) }}" class="btn btn-sm btn-success">Download</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No schedules created yet.</p>
            @endif
        </div>
    </div>
</div>


        <!-- Pay Slip Tab -->
        <div class="tab-pane fade" id="payslip" role="tabpanel">
            <div class="card">
                <div class="card-header">Employee Pay Slip Record</div>
                <div class="card-body">
                    <p class="text-muted">Pay slip functionality coming soon...</p>
                </div>
            </div>
        </div>

    </div>
    <!-- ✅ END of tab-content -->
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('employees.schedules.store', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">
                    <!-- Left: Options -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Weeks</label>
                            <select name="weeks" id="weeks" class="form-control" required>
                                <option value="1">1 Week</option>
                                <option value="2">2 Weeks</option>
                                <option value="3">3 Weeks</option>
                                <option value="4">4 Weeks</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Time In</label>
                            <input type="time" name="time_in" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Time Out</label>
                            <input type="time" name="time_out" class="form-control" required>
                        </div>

                        <!-- Toggle Buttons -->
                        <div class="mb-3">
                            <label class="form-label">Mark Days As:</label>
                            <div class="btn-group w-100">
                                <button type="button" id="btn-working" class="btn btn-outline-primary active">Working Day</button>
                                <button type="button" id="btn-rest" class="btn btn-outline-danger">Rest Day</button>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Calendar -->
                    <div class="col-md-8">
                        <div id="mini-calendar"></div>
                        <input type="hidden" name="days_json" id="days-json">
                        <small class="text-muted">
                            Select up to <span id="max-days-label">7</span> days (based on weeks).
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- FullCalendar CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.9/index.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.9/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('mini-calendar');
    let activeType = 'working'; // default selection
    let weeksSelect = document.getElementById('weeks');
    let maxDaysLabel = document.getElementById('max-days-label');

    // Toggle buttons
    document.getElementById('btn-working').addEventListener('click', function(){
        activeType = 'working';
        this.classList.add('active');
        document.getElementById('btn-rest').classList.remove('active');
    });

    document.getElementById('btn-rest').addEventListener('click', function(){
        activeType = 'restday';
        this.classList.add('active');
        document.getElementById('btn-working').classList.remove('active');
    });

    function getMaxDays() {
        let weeks = parseInt(weeksSelect.value);
        let maxDays = weeks * 7;
        maxDaysLabel.textContent = maxDays;
        return maxDays;
    }

    if (calendarEl) {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            height: 350, // smaller height
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            dateClick: function(info) {
                let cell = info.dayEl;

                // ✅ Prevent re-selecting same date
                if (cell.classList.contains('working') || cell.classList.contains('restday')) {
                    return; 
                }

                let maxDays = getMaxDays();
                let selectedDays = document.querySelectorAll('#mini-calendar .working, #mini-calendar .restday').length;

                if (selectedDays >= maxDays) {
                    alert("You can only select up to " + maxDays + " days.");
                    return;
                }

                if (activeType === 'working') {
                    cell.classList.add('working');
                    cell.style.backgroundColor = '#cfe2ff';
                } else {
                    cell.classList.add('restday');
                    cell.style.backgroundColor = '#f8d7da';
                }

                updateDaysJson();
            }

        });

        calendar.render();

        function updateDaysJson() {
            let days = [];
            document.querySelectorAll('#mini-calendar .fc-day').forEach(function(day) {
                let date = day.getAttribute('data-date');
                if (day.classList.contains('restday')) {
                    days.push({date: date, type: 'restday'});
                } else if (day.classList.contains('working')) {
                    days.push({date: date, type: 'working'});
                }
            });
            document.getElementById('days-json').value = JSON.stringify(days);
        }
    }
});
</script>

<style>
/* Mini Calendar Styling */
#mini-calendar {
    max-width: 100%;
    font-size: 12px;
}
.fc-daygrid-day-frame {
    padding: 2px !important;
}
.fc-day.restday { 
    background-color: #f8d7da !important; 
    color: #721c24 !important;
}
.fc-day.working { 
    background-color: #cfe2ff !important; 
    color: #084298 !important;
}
.fc-day:hover {
    cursor: pointer;
    opacity: 0.8;
}
</style>
@endpush

@endsection
