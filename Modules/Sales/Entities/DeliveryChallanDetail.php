<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\Product;
use Modules\Sales\Entities\DeliveryChallan;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\SupplyChain\Entities\MeasuringUnit;

class DeliveryChallanDetail extends Model
{
    protected $guarded = [];

    public function deliveryChallan()
    {
        return $this->belongsTo(DeliveryChallan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function productSize()
    // {
    //     return $this->belongsTo(ProductSize::class, 'product_size_id', 'id');
    // }

    public function measuringUnit()
    {
        return $this->belongsTo(MeasuringUnit::class);
    }
}
