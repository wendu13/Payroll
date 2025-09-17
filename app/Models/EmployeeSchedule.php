<?php

// EmployeeSchedule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'cutoff_schedule_id',
        'schedule_file_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
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

    public function scheduleFile()
    {
        return $this->belongsTo(ScheduleFile::class);
    }
}
