<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class FixAttendance extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id' , 'id');
    }
}
