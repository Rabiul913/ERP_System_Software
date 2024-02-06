<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\Product;
use Modules\SupplyChain\Entities\ProductSize;

class SalesOrderDetail extends Model
{
    use \App\Traits\CreateComId;
    protected $guarded = [];


    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id', 'id');
    }
}
