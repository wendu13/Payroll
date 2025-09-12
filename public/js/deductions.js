document.addEventListener('DOMContentLoaded', function() {
    const daysInput = document.getElementById('daysInput');
    const rateTypeSelect = document.getElementById('rateTypeSelect');
    const rateInput = document.getElementById('rateInput');
    const perDaySpan = document.getElementById('perDay');
    const perHourSpan = document.getElementById('perHour');
    const perMinuteSpan = document.getElementById('perMinute');
    const dailyResultContainer = document.getElementById('dailyResultContainer');
    
    // Mode elements
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    const viewModeButtons = document.getElementById('viewModeButtons');
    const editModeButtons = document.getElementById('editModeButtons');
    const viewDays = document.getElementById('viewDays');
    const editButton = document.getElementById('editButton');
    const cancelEditButton = document.getElementById('cancelEditButton');
    
    // ✅ Get data from data attribute
    const modal = document.getElementById('lateAbsenceModal');
    const savedDaysValue = modal.getAttribute('data-saved-days');
    const hasSavedData = savedDaysValue !== '' && parseFloat(savedDaysValue) > 0;
    let originalDaysValue = daysInput.value;
    
    function showViewMode() {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        viewModeButtons.style.display = 'block';
        editModeButtons.style.display = 'none';
        viewDays.textContent = daysInput.value;
    }
    
    function showEditMode() {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        viewModeButtons.style.display = 'none';
        editModeButtons.style.display = 'block';
        originalDaysValue = daysInput.value;
        updateSample();
    }
    
    // Initialize modal based on saved data
    if (hasSavedData) {
        showViewMode();
    } else {
        showEditMode();
    }
    
    // Edit button click
    editButton.addEventListener('click', function() {
        showEditMode();
    });
    
    // Cancel edit button click
    cancelEditButton.addEventListener('click', function() {
        daysInput.value = originalDaysValue;
        if (hasSavedData) {
            showViewMode();
        } else {
            // If no saved data, close modal instead
            const modal = bootstrap.Modal.getInstance(document.getElementById('lateAbsenceModal'));
            modal.hide();
        }
    });

    function updateSample() {
        const days = parseFloat(daysInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;

        let perDay = 0;
        let perHour = 0;
        let perMinute = 0;

        if (rateTypeSelect.value === 'daily') {
            // Daily rate: days input not used
            perDay = 0;               // hide, not used
            perHour = rate / 8;       // compute per hour
            perMinute = perHour / 60;
            dailyResultContainer.style.display = 'none'; // hide per day
        } else if (rateTypeSelect.value === 'monthly') {
            // For monthly rate, divide by the number of days set (not 30)
            const dailyRate = days > 0 ? rate / days : 0;
            perDay = dailyRate;
            perHour = dailyRate / 8;
            perMinute = perHour / 60;
            dailyResultContainer.style.display = 'block';
        }

        perDaySpan.textContent = perDay.toFixed(2);
        perHourSpan.textContent = perHour.toFixed(2);
        perMinuteSpan.textContent = perMinute.toFixed(2);
    }

    daysInput.addEventListener('input', updateSample);
    rateTypeSelect.addEventListener('change', updateSample);
    rateInput.addEventListener('input', updateSample);

    updateSample(); // initialize
});

document.addEventListener('DOMContentLoaded', function() {
    const savedBrackets = document.getElementById('savedBrackets');
    const newBrackets = document.getElementById('newBrackets');
    const addButton = document.getElementById('addBracket');

    function updateTotal(row) {
        const er = parseFloat(row.querySelector('.erInput')?.value) || 0;
        const ee = parseFloat(row.querySelector('.eeInput')?.value) || 0;
        row.querySelector('.totalInput').value = (er + ee).toFixed(2);
    }

    // Auto compute total for both saved and new rows
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('erInput') || e.target.classList.contains('eeInput')) {
            const row = e.target.closest('tr');
            updateTotal(row);
        }
    });

    // Add new bracket row
    addButton.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="number" name="from" class="form-control fromInput" required></td>
            <td><input type="number" name="to" class="form-control toInput" required></td>
            <td><input type="number" name="er" class="form-control erInput" required></td>
            <td><input type="number" name="ee" class="form-control eeInput" required></td>
            <td><input type="number" class="form-control totalInput" readonly></td>
            <td><input type="text" name="others" class="form-control othersInput"></td>
            <td>
                <form method="POST" action="/deductions/sss" class="d-inline">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </form>
                <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
            </td>
        `;
        newBrackets.appendChild(row);
    });

    // Remove new bracket row
    newBrackets.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // Edit saved bracket row
    savedBrackets.addEventListener('click', function(e) {
        if (e.target.classList.contains('editRow')) {
            const row = e.target.closest('tr');
            row.querySelectorAll('input').forEach(input => {
                if (!input.classList.contains('totalInput')) input.removeAttribute('readonly');
            });
            e.target.classList.add('d-none'); // hide Edit
            row.querySelector('.saveRow').classList.remove('d-none'); // show Save
        }
    });
});

window.csrfToken = '{{ csrf_token() }}';




