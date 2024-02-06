<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function leave_entry()
    {
        return $this->hasMany(LeaveEntry::class, 'leave_type_id', 'id');
    }
}
