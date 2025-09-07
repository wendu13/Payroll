<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionType extends Model
{
    protected $fillable = ['name', 'type', 'is_active'];

    public function rules()
    {
        return $this->hasMany(DeductionRule::class);
    }
}

