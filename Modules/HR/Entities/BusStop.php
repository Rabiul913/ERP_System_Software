<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];
}
