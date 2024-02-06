<?php

namespace Modules\Sales\Entities;

use Modules\Sales\Entities\Customer;
use Modules\Sales\Entities\SalesOrder;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\Stock;
use Modules\Sales\Entities\DeliveryOrder;
use Modules\Sales\Entities\DeliveryChallanDetail;

class DeliveryChallan extends Model
{
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function deliveryChallanDetails()
    {
        return $this->hasMany(DeliveryChallanDetail::class);
    }
    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function sales_return()
    {
        return $this->hasMany(SalesReturn::class);
    }

    public function stocks()
    {
        return $this->morphMany(Stock::class, 'stockable');
    }
}
