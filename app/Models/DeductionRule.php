<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionRule extends Model
{
    protected $fillable = ['deduction_type_id', 'min_salary', 'max_salary', 'value', 'value_type'];

    public function deductionType()
    {
        return $this->belongsTo(DeductionType::class);
    }
}
