<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class AttendanceRow extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'card_no' , 'card_no');
    // }
}
