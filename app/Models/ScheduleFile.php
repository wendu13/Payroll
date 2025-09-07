<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'cutoff_schedule_id',
        'time_in',
        'time_out',
        'weeks',
        'days_json', // optional, kung gusto mo i-store selection
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cutoff()
    {
        return $this->belongsTo(CutoffSchedule::class, 'cutoff_schedule_id');
    }

    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'schedule_file_id');
    }
}
