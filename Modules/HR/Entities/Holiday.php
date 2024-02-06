<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];
}
