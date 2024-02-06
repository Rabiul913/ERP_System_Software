<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class SubSection extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'sub_section_id', 'id');
    }
}
