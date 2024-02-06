<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;

class SalesZone extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];
}
