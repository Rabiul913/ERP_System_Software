<?php

namespace Modules\Sales\Entities;

use Modules\Sales\Entities\Customer;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Entities\SalesOrderDetail;

class SalesOrder extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }


    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id', 'id');
    }
}
