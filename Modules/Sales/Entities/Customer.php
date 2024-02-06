<?php

namespace Modules\Sales\Entities;

use Modules\HR\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    use \App\Traits\CreateComId;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(SalesZone::class, 'zone_id', 'id');
    }
}
