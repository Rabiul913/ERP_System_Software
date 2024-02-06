<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class PayMode extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function employee_bank_infos()
    {
        return $this->hasMany(EmployeeBankInfo::class, 'pay_mode_id', 'id');
    }
}
