<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function sales_return_details()
    {
        return $this->hasMany(SalesReturnDetail::class, 'sales_return_id', 'id');
    }

    public function delivery_challan(){
        return $this->belongsTo(DeliveryChallan::class, 'delivery_challan_id', 'id');

    }

    public function stock(){
        return $this->morphMany(Stock::class, 'stockable');

    }


}
