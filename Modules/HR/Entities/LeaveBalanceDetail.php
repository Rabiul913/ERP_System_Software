<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveBalanceDetail extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    
    public function leave_balance()
    {
        return $this->belongsTo(LeaveBalance::class, 'leave_balance_id' , 'id');
    }
}
