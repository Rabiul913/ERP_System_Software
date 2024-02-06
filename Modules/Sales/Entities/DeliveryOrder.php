<?php

namespace Modules\Sales\Entities;

use Modules\Admin\Entities\User;
use Modules\HR\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class deliveryOrder extends Model
{
    protected $guarded = [];
    use \App\Traits\CreateComId;


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function deliveryOrderDetails()
    {
        return $this->hasMany(DeliveryOrderDetail::class, 'delivery_order_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(SalesZone::class, 'zone_id', 'id');
    }

    public function deliveryChallans(){
        return $this->hasMany(DeliveryChallan::class, 'delivery_order_id');
    }

    public function completedby(){
        return $this->belongsTo(User::class, 'completed_by');
    }
}
