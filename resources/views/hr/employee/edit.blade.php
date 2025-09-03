@extends('layouts.hr')

@section('content')
{{-- Fixed Top Bar --}}
<div class="fixed-top-bar" style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Edit Employee</h2>
</div>

<form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data" class="p-4">
    @csrf

    <div class="add-employee-form-wrapper">

        {{-- Main Form Table --}}
        <div class="add-employee-form-table">
            <table class="table table-sm table-bordered mt-3">
                <tbody>
                    <tr>
                        <td>Department</td>
                        <td><input type="text" name="department" class="form-control" value="{{ old('department', $employee->department) }}"></td>
                        <td>Position</td>
                        <td><input type="text" name="position" class="form-control" value="{{ old('position', $employee->position) }}"></td>
                        <td>Employee Number</td>
                        <td><input type="text" name="employee_number" class="form-control" value="{{ $employee->employee_number }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type="text" name="last_name" class="form-control" value="{{ $employee->last_name }}" readonly></td>
                        <td>First Name</td>
                        <td><input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}" readonly></td>
                        <td>Middle Name</td>
                        <td><input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $employee->middle_name) }}"></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><input type="text" name="address" class="form-control" value="{{ old('address', $employee->address) }}"></td>
                        <td>Zip Code</td>
                        <td><input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $employee->zip_code) }}"></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><input type="text" name="contact" class="form-control" value="{{ old('contact', $employee->contact) }}"></td>
                        <td>Email</td>
                        <td><input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}"></td>
                        <td>Phone</td>
                        <td><input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}"></td>
                    </tr>
                    <tr>
                        <td>Birthplace</td>
                        <td><input type="text" name="birthplace" class="form-control" value="{{ old('birthplace', $employee->birthplace) }}"></td>
                        <td>Birthdate</td>
                        <td><input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ old('birthdate', $employee->birthdate) }}"></td>
                        <td>Age</td>
                        <td><input type="number" name="age" id="age" class="form-control" value="{{ old('age', $employee->age) }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            <select name="gender" class="form-control">
                                <option value="">Select</option>
                                <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </td>
                        <td>Civil Status</td>
                        <td>
                            <select name="civil_status" class="form-control">
                                <option value="">Select</option>
                                <option value="Single" {{ $employee->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $employee->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ $employee->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Religion</td>
                        <td><input type="text" name="religion" class="form-control" value="{{ old('religion', $employee->religion) }}"></td>
                        <td>Nationality</td>
                        <td><input type="text" name="nationality" class="form-control" value="{{ old('nationality', $employee->nationality) }}"></td>
                    </tr>
                    <tr>
                        <td>SSS</td>
                        <td><input type="text" name="sss" class="form-control" value="{{ old('sss', $employee->sss) }}"></td>
                        <td>PhilHealth</td>
                        <td><input type="text" name="philhealth" class="form-control" value="{{ old('philhealth', $employee->philhealth) }}"></td>
                    </tr>
                    <tr>
                        <td>PAGIBIG</td>
                        <td><input type="text" name="pagibig" class="form-control" value="{{ old('pagibig', $employee->pagibig) }}"></td>
                        <td>TIN</td>
                        <td><input type="text" name="tin" class="form-control" value="{{ old('tin', $employee->tin) }}"></td>
                    </tr>
                    <tr>
                        <td>Workday</td>
                        <td><input type="text" name="workday" class="form-control" value="{{ old('workday', $employee->workday) }}"></td>
                        <td>Restday</td>
                        <td><input type="text" name="restday" class="form-control" value="{{ old('restday', $employee->restday) }}"></td>
                    </tr>
                    <tr>
                        <td>Bank Name</td>
                        <td><input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $employee->bank_name) }}"></td>
                        <td>Bank Account</td>
                        <td><input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $employee->bank_account) }}"></td>
                    </tr>
                    <tr>
                        <td>Basic Rate (Monthly)</td>
                        <td><input type="number" name="basic_rate" class="form-control" value="{{ old('basic_rate', $employee->basic_rate) }}"></td>
                        <td>Allowance</td>
                        <td><input type="number" name="allowance" class="form-control" value="{{ old('allowance', $employee->allowance) }}"></td>
                    </tr>
                    <tr>
                        <td>Other Pay</td>
                        <td colspan="3"><input type="number" name="other_pay" class="form-control" value="{{ old('other_pay', $employee->other_pay) }}"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Profile Picture --}}
        <div class="add-employee-profile">
            <label>Profile Picture</label>
            @if($employee->photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Profile" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @endif
            <input type="file" name="photo" accept="image/*" onchange="previewImage(this)">
            <div class="preview-box">
                <img id="preview" src="#" alt="Preview" class="preview-img">
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="form-actions">
        <a href="{{ route('employees.index') }}" class="btn">Cancel</a>
        <button type="submit" class="btn">Update</button>
    </div>
</form>

{{-- JS for Image Preview --}}
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<script>
    document.getElementById('birthdate').addEventListener('change', function () {
        const birthdate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const m = today.getMonth() - birthdate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }

        document.getElementById('age').value = age;
    });
</script>
@endsection
