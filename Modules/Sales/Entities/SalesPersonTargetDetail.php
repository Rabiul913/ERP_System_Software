<?php

namespace Modules\Sales\Entities;

use Modules\HR\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Entities\SalesPersonTarget;

class SalesPersonTargetDetail extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function sales_target()
    {
        return $this->belongsTo(SalesPersonTarget::class, 'sales_person_target_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
