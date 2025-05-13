<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtRequest extends Model
{
    protected $fillable = [
        'user_id',
        'ot_date',
        'ot_start_time',
        'ot_end_time',
        'ot_hours',
        'ot_reason',
    ];    
}
