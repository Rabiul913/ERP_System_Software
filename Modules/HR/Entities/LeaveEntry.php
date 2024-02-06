<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveEntry extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function leave_type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id' , 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id' , 'id');
    }
}
