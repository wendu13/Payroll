<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeSchedule;
use App\Models\CutoffSchedule;

class HrController extends Controller
{
    public function dashboard()
    {
        return view('hr.dashboard');
    }

    // Employee index with department filter
    public function employeeIndex(Request $request)
    {
        $selectedDepartment = $request->input('department');
    
        $employees = Employee::when($selectedDepartment, function ($query) use ($selectedDepartment) {
            return $query->where('department', $selectedDepartment);
        })->get();
    
        $departments = Employee::select('department')->distinct()->pluck('department');
    
        return view('hr.employee.index', compact('employees', 'selectedDepartment', 'departments'));
    }

    public function addEmployee(Request $request)
    {
    // Validate inputs
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'employee_number' => 'required|unique:employees',
        'department' => 'required|string',
        'profile' => 'nullable|image|max:2048',
        // Add other fields as needed
    ]);

    // Create employee
    $employee = new Employee;
    $employee->department = $request->department;
    $employee->employee_number = $request->employee_number;
    $employee->position = $request->position;
    $employee->last_name = $request->last_name;
    $employee->first_name = $request->first_name;
    $employee->middle_name = $request->middle_name;
    $employee->address = $request->address;
    $employee->zip_code = $request->zip_code;
    $employee->contact = $request->contact;
    $employee->email = $request->email;
    $employee->phone = $request->phone;
    $employee->birthplace = $request->birthplace;
    $employee->birthdate = $request->birthdate;
    $employee->age = $request->age;
    $employee->gender = $request->gender;
    $employee->civil_status = $request->civil_status;
    $employee->religion = $request->religion;
    $employee->nationality = $request->nationality;
    $employee->sss = $request->sss;
    $employee->philhealth = $request->philhealth;
    $employee->pagibig = $request->pagibig;
    $employee->tin = $request->tin;
    $employee->workday = $request->workday;
    $employee->restday = $request->restday;
    $employee->bank_name = $request->bank_name;
    $employee->bank_account = $request->bank_account;
    $employee->basic_rate = $request->basic_rate;
    $employee->allowance = $request->allowance;
    $employee->other_pay = $request->other_pay;

    // Handle employee photo upload
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('employee_photos', 'public');
        $employee->photo = $photoPath;
    }


    $employee->save();

    return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function create() {
        return view('hr.employee.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // other fields...
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        $employee = new Employee($request->except('photo'));
    
        if ($request->hasFile('photo')) {
            // save in storage/app/public/photos
            $path = $request->file('photo')->store('photos', 'public');
            $employee->photo = $path; // e.g. "photos/profile123.jpg"
        }
    
        $employee->save();
    
        return redirect()->route('employees.index')->with('success', 'Employee added successfully');
    }    

    public function showAddEmployeeForm()
    {
        $departments = DB::table('employees')
            ->select('department')
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department', 'asc')
            ->get();
    
        return view('hr.employee.add', compact('departments'));
    }

    public function viewEmployee($id)
    {
        // Load employee with schedules + cutoff
        $employee = Employee::with(['schedules.cutoff'])->findOrFail($id);
    
        return view('hr.employee.view', compact('employee'));
    }

    public function index(Request $request)
    {
        $query = DB::table('employees');

        // Filter by search keyword
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('contact', 'like', '%' . $request->search . '%')
                ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Sort by last name and paginate (20 per page)
        $employees = $query->orderBy('last_name', 'asc')->paginate(20);

        // Fetch distinct departments for the dropdown
        $departments = DB::table('employees')->select('department')->distinct()->pluck('department');

        return view('hr.employees.index', compact('employees', 'departments'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('hr.employee.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'last_name'   => 'required|string',
            'first_name'  => 'required|string',
            'middle_name' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
            // add all your other fields...
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function storeSchedule(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'cutoff' => 'required|in:1st_half,2nd_half',
            'weeks' => 'required|integer|min:1|max:4',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i',
            'days_json' => 'nullable|string',
        ]);
    
        // Get the first cutoff schedule (you can modify this logic)
        $cutoff = CutoffSchedule::first();
        if (!$cutoff) {
            return back()->with('error', 'No cutoff schedule found. Please set up cutoff schedules first.');
        }
    
        // Parse calendar selections
        $daysData = [];
        if ($validated['days_json']) {
            $daysData = json_decode($validated['days_json'], true) ?: [];
        }
    
        // Calculate date range
        $startDate = now();
        $endDate = now()->addWeeks($validated['weeks'])->subDay();
    
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate->copy()->addDay()
        );
    
        $successCount = 0;
        
        // Create schedule entries
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            // Check if this date has specific calendar settings
            $dayType = 'regular'; // default
            foreach ($daysData as $dayData) {
                if (isset($dayData['date']) && $dayData['date'] === $dateStr) {
                    $dayType = $dayData['type'] === 'restday' ? 'restday' : 'regular';
                    break;
                }
            }
    
            // Create the schedule record
            EmployeeSchedule::create([
                'employee_id' => (int)$employeeId,
                'cutoff_schedule_id' => $cutoff->id,
                'date' => $dateStr,
                'start_time' => $validated['time_in'],
                'end_time' => $validated['time_out'],
                'type' => $dayType === 'restday' ? 'rest' : 'work',
            ]);
            
            $successCount++;
        }
    
        return back()->with('success', "Schedule created successfully! Added {$successCount} days for {$validated['weeks']} week(s).");
    }

    
}
