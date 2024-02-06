<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class SalarySetting extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];
    
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id', 'id');
    }
}
