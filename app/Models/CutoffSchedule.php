<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutoffSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'cutoff_half',
        'first_half_start',
        'first_half_end',
        'second_half_start',
        'second_half_end',
        'regular_start',
        'regular_end',
        'night_start',
        'night_end',
    ];

    // Relationships
    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function scheduleFiles()
    {
        return $this->hasMany(ScheduleFile::class);
    }
}
