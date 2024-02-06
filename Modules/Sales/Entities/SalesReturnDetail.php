<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\MeasuringUnit;
use Modules\SupplyChain\Entities\Product;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\SupplyChain\Entities\ProductType;
use Modules\SupplyChain\Entities\Warehouse;

class SalesReturnDetail extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];

    public function sales_return()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
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
        return $this->belongsTo(MeasuringUnit::class, 'measuring_unit_id', 'id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
