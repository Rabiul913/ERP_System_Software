<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;

class SalesCollection extends Model
{
    protected $guarded = [];
    use \App\Traits\CreateComId;


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
