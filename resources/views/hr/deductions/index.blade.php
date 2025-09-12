@extends('layouts.hr')

@section('page-title', 'Deduction Types')

@section('hr-content')
<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Deduction Type</th>
            <th>Last Update</th>
        </tr>
    </thead>
    <tbody>
        <!-- Late and Absences -->
        <tr data-bs-toggle="modal" data-bs-target="#lateAbsenceModal" style="cursor:pointer;">
            <td>Late and Absences</td>
            <td>{{ $lateAbsence?->updated_at?->format('M. d, Y h:i A') ?? 'Not yet set' }}</td>
        </tr>
        <!-- Loans and Advances -->
        <tr data-bs-toggle="modal" data-bs-target="#loansModal" style="cursor:pointer;">
            <td>Loans and Advances</td>
            <td>Not yet set</td>
        </tr>

        <!-- SSS Contribution -->
        <tr data-bs-toggle="modal" data-bs-target="#sssModal" style="cursor:pointer;">
            <td>SSS Contribution</td>
            <td>
                {{ $sssBrackets && count($sssBrackets) > 0 
                    ? $sssBrackets->last()->updated_at->format('M. d, Y h:i A') 
                    : 'Not yet set' }}
            </td>
        </tr>

    </tbody>
</table>

<!-- Modal for Late and Absences -->
<div class="modal fade" id="lateAbsenceModal" tabindex="-1" aria-hidden="true"
     data-saved-days="{{ $lateAbsence->settings['days'] ?? '' }}">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('deductions.update', $lateAbsence->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setup: Late and Absences</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- VIEW MODE -->
                    <div id="viewMode" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label"><strong>Number of Days:</strong></label>
                            <p id="viewDays" class="form-control-plaintext">{{ $lateAbsence->settings['days'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- EDIT MODE -->
                    <div id="editMode">
                        <!-- Number of Days -->
                        <div class="mb-3">
                            <label class="form-label">Number of Days</label>
                            <input type="number" step="0.01" name="days" id="daysInput"
                                   value="{{ $lateAbsence->settings['days'] ?? 0 }}"
                                   class="form-control" required>
                        </div>

                        <hr>
                        <!-- Sample Computation -->
                        <h6>Sample Computation</h6>

                        <div class="mb-3">
                            <label class="form-label">Rate Type</label>
                            <select id="rateTypeSelect" class="form-select">
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" id="rateInput" class="form-control" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Computed Deduction:</label>
                            <p id="dailyResultContainer">Per Day: <span id="perDay">0</span></p>
                            <p>Per Hour: <span id="perHour">0</span></p>
                            <p>Per Minute: <span id="perMinute">0</span></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- VIEW MODE BUTTONS -->
                    <div id="viewModeButtons" style="display: none;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="editButton">Edit</button>
                    </div>
                    
                    <!-- EDIT MODE BUTTONS -->
                    <div id="editModeButtons">
                        <button type="button" class="btn btn-outline-secondary" id="cancelEditButton">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loans and Advances Modal -->
<div class="modal fade" id="loansModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loans and Advances</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>
                    Loans and Advances are employee-specific deductions. Each loan or advance is linked 
                    to an individual employee, including the amount, date, and payment schedule.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SSS Contribution Modal -->
<div class="modal fade" id="sssModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">SSS Contribution Brackets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Employer (ER)</th>
                            <th>Employee (EE)</th>
                            <th>Total</th>
                            <th>Others</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <!-- Existing Brackets -->
                    <tbody id="savedBrackets">
                        @foreach($sssBrackets as $bracket)
                        <tr>
                            <form method="POST" action="{{ route('deductions.sss.update', $bracket->id) }}">
                                @csrf
                                @method('PUT')
                                <td><input type="number" name="from" class="form-control fromInput" value="{{ $bracket->from }}" readonly></td>
                                <td><input type="number" name="to" class="form-control toInput" value="{{ $bracket->to }}" readonly></td>
                                <td><input type="number" name="er" class="form-control erInput" value="{{ $bracket->er }}" readonly></td>
                                <td><input type="number" name="ee" class="form-control eeInput" value="{{ $bracket->ee }}" readonly></td>
                                <td><input type="number" name="total" class="form-control totalInput" value="{{ $bracket->total }}" readonly></td>
                                <td><input type="text" name="others" class="form-control othersInput" value="{{ $bracket->others }}" readonly></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary editRow">Edit</button>
                                    <button type="submit" class="btn btn-sm btn-success saveRow d-none">Save</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>

                    <!-- New Brackets -->
                    <tbody id="newBrackets"></tbody>
                </table>

                <button type="button" id="addBracket" class="btn btn-success mt-2">Add Bracket</button>
            </div>
        </div>
    </div>
</div>

<!-- SSS Contribution Modal -->
<div class="modal fade" id="sssModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SSS Contribution Brackets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Employer (ER)</th>
                            <th>Employee (EE)</th>
                            <th>Total</th>
                            <th>Others</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <!-- Existing brackets -->
                    <tbody id="savedBrackets">
                        @foreach($sssBrackets as $bracket)
                        <tr data-id="{{ $bracket->id }}">
                            <td><input type="number" class="form-control fromInput" value="{{ $bracket->from }}" readonly></td>
                            <td><input type="number" class="form-control toInput" value="{{ $bracket->to }}" readonly></td>
                            <td><input type="number" class="form-control erInput" value="{{ $bracket->er }}" readonly></td>
                            <td><input type="number" class="form-control eeInput" value="{{ $bracket->ee }}" readonly></td>
                            <td><input type="number" class="form-control totalInput" value="{{ $bracket->total }}" readonly></td>
                            <td><input type="text" class="form-control othersInput" value="{{ $bracket->others }}" readonly></td>
                            <td>
                                <form method="POST" action="{{ route('deductions.sss.update', $bracket->id) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm saveRow d-none">Save</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-primary editRow">Edit</button>
                                <form method="POST" action="{{ route('deductions.sss.destroy', $bracket->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <!-- New brackets -->
                    <tbody id="newBrackets"></tbody>
                </table>

                <button type="button" id="addBracket" class="btn btn-success mt-2">Add Bracket</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/deductions.js') }}"></script>
@endsection