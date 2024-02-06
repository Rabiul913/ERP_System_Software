<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'floor_id', 'id');
    }
    public function buildingInfo()
    {
        return $this->belongsTo(BuidingInfo::class, 'building_info_id' , 'id');
    }
}
