<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'id');
    }
}
