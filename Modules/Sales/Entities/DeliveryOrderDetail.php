<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\Product;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\SupplyChain\Entities\MeasuringUnit;

class deliveryOrderDetail extends Model
{
    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // public function size()
    // {
    //     return $this->belongsTo(ProductSize::class, 'size_id', 'id');
    // }

    public function unit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'unit_id', 'id');
    }

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id', 'id');
    }
}
