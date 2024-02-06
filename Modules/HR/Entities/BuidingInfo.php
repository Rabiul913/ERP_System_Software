<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class BuidingInfo extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function floors()
    {
        return $this->hasMany(Floor::class, 'building_info_id', 'id');
    }
}
