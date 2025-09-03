<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Allow these fields to be mass-assigned
    protected $fillable = [
        'employee_number',  // This field is used in your view
        'first_name',
        'last_name',
        'middle_name',
        'position',
        'department',
        'address',
        'zip_code',
        'contact',
        'email',
        'phone',
        'birthplace',
        'birthdate',
        'age',
        'gender',
        'civil_status',
        'religion',
        'nationality',
        'sss',
        'philhealth',
        'pagibig',
        'tin',
        'bank_name',
        'bank_account',
        'basic_rate',
        'allowance',
        'other_pay',
        'photo',
        // ... other fields
    ];
    
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }
    
}


