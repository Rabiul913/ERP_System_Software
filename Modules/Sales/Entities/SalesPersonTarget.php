<?php

namespace Modules\Sales\Entities;

use Modules\HR\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Entities\SalesPersonTargetDetail;

class SalesPersonTarget extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];




    public function target_order_details()
    {
        return $this->hasMany(SalesPersonTargetDetail::class, 'sales_person_target_id', 'id');
    }
}
