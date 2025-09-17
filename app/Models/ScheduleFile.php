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
        'days_json',
    ];

    protected $casts = [
        'days_json' => 'array', // This will automatically handle JSON encoding/decoding
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cutoff()
    {
        return $this->belongsTo(CutoffSchedule::class, 'cutoff_schedule_id');
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }
}