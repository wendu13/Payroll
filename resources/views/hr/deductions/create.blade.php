@extends('layouts.hr')

@section('page-title', 'Add Deduction')

@section('hr-content')
<form action="{{ route('deductions.store') }}" method="POST">
    @csrf

        {{-- Deduction Name (Dropdown) --}}
        <div class="mb-3">
            <label class="form-label">Deduction Type</label>
            <select id="deductionName" name="name" class="form-control" required>
                <option value="Late and absences">Late and absences</option>
                <option value="Loans and advances">Loans and advances</option>
                <option value="SSS contribution">SSS contribution</option>
                <option value="PHIC contribution">PHIC contribution</option>
                <option value="HDMF contribution">HDMF contribution</option>
                <option value="SSS loan">SSS loan</option>
                <option value="HDMF loan">HDMF loan</option>
                <option value="Tax">Tax</option>
                <option value="others">Others (Specify)</option>
            </select>
        </div>

        {{-- Others: Custom Name --}}
        <div class="mb-3 d-none" id="otherNameWrapper">
            <label class="form-label">Specify Deduction</label>
            <input type="text" name="custom_name" class="form-control" placeholder="Enter deduction name">
        </div>

        {{-- Deduction Method --}}
        <div class="mb-3">
            <label class="form-label">Computation Type</label>
            <select id="deductionType" name="type" class="form-control" required>
                <option value="manual">Manual</option>
                <option value="fixed">Fixed</option>
                <option value="percent">Percent</option>
                <option value="bracket">Bracket</option>
            </select>
        </div>

        {{-- Extra Fields (Dynamic Inputs) --}}
        <div id="extraFields"></div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('deductions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    const deductionName = document.getElementById('deductionName');
    const otherNameWrapper = document.getElementById('otherNameWrapper');
    const deductionType = document.getElementById('deductionType');
    const extraFields = document.getElementById('extraFields');

    deductionName.addEventListener('change', (e) => {
        otherNameWrapper.classList.toggle('d-none', e.target.value !== 'others');
    });

    function renderFields(type) {
        extraFields.innerHTML = '';
        if (type === 'fixed') {
            extraFields.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Fixed Amount</label>
                    <input type="number" step="0.01" name="value" class="form-control" required>
                    <input type="hidden" name="value_type" value="fixed">
                </div>`;
        }
        if (type === 'percent') {
            extraFields.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Percentage (%)</label>
                    <input type="number" step="0.01" name="value" class="form-control" required>
                    <input type="hidden" name="value_type" value="percent">
                </div>`;
        }
        if (type === 'bracket') {
            extraFields.innerHTML = `
                <h5>Brackets</h5>
                <div id="brackets">
                    <div class="row mb-2">
                        <div class="col"><input type="number" step="0.01" name="min_salary[]" class="form-control" placeholder="From" required></div>
                        <div class="col"><input type="number" step="0.01" name="max_salary[]" class="form-control" placeholder="To" required></div>
                        <div class="col"><input type="number" step="0.01" name="employer[]" class="form-control" placeholder="Employer" required></div>
                        <div class="col"><input type="number" step="0.01" name="employee[]" class="form-control" placeholder="Employee" required></div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBracket()">+ Add Bracket</button>`;
        }
    }

    function addBracket() {
        document.getElementById('brackets').insertAdjacentHTML('beforeend', `
            <div class="row mb-2">
                <div class="col"><input type="number" step="0.01" name="min_salary[]" class="form-control" placeholder="From" required></div>
                <div class="col"><input type="number" step="0.01" name="max_salary[]" class="form-control" placeholder="To" required></div>
                <div class="col"><input type="number" step="0.01" name="employer[]" class="form-control" placeholder="Employer" required></div>
                <div class="col"><input type="number" step="0.01" name="employee[]" class="form-control" placeholder="Employee" required></div>
            </div>`);
    }

    renderFields(deductionType.value);
    deductionType.addEventListener('change', (e) => renderFields(e.target.value));
</script>
@endsection
