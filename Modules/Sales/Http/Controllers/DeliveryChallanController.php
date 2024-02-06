<?php

namespace Modules\Sales\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\Customer;
use Modules\Sales\Entities\SalesOrder;
use Modules\SupplyChain\Entities\Stock;
use Modules\Sales\Entities\DeliveryOrder;
use Modules\SupplyChain\Entities\Product;
use Modules\Sales\Entities\DeliveryChallan;
use Modules\SupplyChain\Entities\Warehouse;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\Sales\Entities\DeliveryOrderDetail;
use Modules\SupplyChain\Entities\MeasuringUnit;

class DeliveryChallanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $deliveryChallans = DeliveryChallan::with('deliveryChallanDetails.product', 'deliveryOrder', 'customer',  'deliveryChallanDetails.measuringUnit')->orderBy('id', 'desc')->get();

        return view('sales::delivery-challan.index', compact('deliveryChallans'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $allProducts = Product::orderBy('name')->pluck('name', 'id');
        $warehouses = Warehouse::orderBy('name')->pluck('name', 'id');
        $deliveryOrders = DeliveryOrder::orderBy('id', 'desc')->where('status', '<>', 1)->pluck('id', 'id')->map(function ($item, $key) {
            return 'DO-' . $item;
        });
        return view('sales::delivery-challan.create', compact('formType', 'customers', 'units', 'deliveryOrders', 'allProducts', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $deliveryChallanData = $request->only('customer_id', 'delivery_order_id', 'delivery_date', 'delivery_address', 'vehicle_no');
            $deliveryChallanDetails = $request->products;
            $stockOutData = [];
            DB::beginTransaction();

            foreach ($deliveryChallanDetails as $deliveryChallanDetail) {
                $doDetalis = DeliveryOrderDetail::where('product_id', $deliveryChallanDetail['product_id'])->where('delivery_order_id', $request->delivery_order_id)->first();
                $doDetalis->update([
                    'remaining_quantity' => $doDetalis->remaining_quantity - $deliveryChallanDetail['quantity']
                ]);

                $stockBatches = Stock::where('product_id', $deliveryChallanDetail['product_id'])->where('quantity', '>', 0)->orderBy('id', 'asc')->get();

                $qtyToIssue = $deliveryChallanDetail['quantity'];

                foreach ($stockBatches as $stockBatch) {
                    $qtyAvailable = $stockBatch->quantity;
                    $qtyToTake = min($qtyAvailable, $qtyToIssue);

                    $stockOutData[] = [
                        'product_id' => $deliveryChallanDetail['product_id'],
                        'batch_id' => $stockBatch->batch_id,
                        'quantity' => -$qtyToTake,
                        'date' => $request->delivery_date,
                        'warehouse_id' => 4,

                    ];

                    $qtyToIssue -= $qtyToTake;

                    if ($qtyToIssue == 0) {
                        break;
                    }
                }
            }

            $deliveryChallan = DeliveryChallan::create($deliveryChallanData);
            $deliveryChallan->deliveryChallanDetails()->createMany($deliveryChallanDetails);
            $deliveryChallan->stocks()->createMany($stockOutData);

            $do = DeliveryOrder::find($request->delivery_order_id);
            if ($do->deliveryOrderDetails()->where('remaining_quantity', '>', 0)->count() == 0) {
                $do->update([
                    'status' => 1
                ]);
            }
            DB::commit();
            $deliveryChallan = DeliveryChallan::with('deliveryChallanDetails.product', 'deliveryOrder.deliveryOrderDetails', 'customer', 'deliveryChallanDetails.measuringUnit')->find($deliveryChallan->id);
            $pdf = PDF::loadView('sales::delivery-challan.invoice', compact('deliveryChallan'),  [], [
                'title' => 'Delivery Challan',
                'format' => 'A4-P',
                'mode' => '',
            ]);
            $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
            $pdf->getMpdf()->showWatermarkText = true;
            return $pdf->stream('Delivery-Challan.pdf');
            return redirect()->route('delivery-challans.index')->with('message', 'Delivery Challan created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('sales::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $formType = 'edit';
        $deliveryChallan = DeliveryChallan::with('deliveryChallanDetails.product.unit', 'deliveryOrder', 'customer',   'deliveryChallanDetails.measuringUnit')->find($id);
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $deliveryOrders = DeliveryOrder::orderBy('id', 'desc')->pluck('id', 'id')->map(function ($item, $key) {
            return 'DO-' . $item;
        });
        $products = Product::orderBy('name')->pluck('name', 'id');
        $sizes = ProductSize::orderBy('name')->pluck('name', 'id');
        return view('sales::delivery-challan.create', compact('formType', 'customers', 'units', 'deliveryOrders', 'deliveryChallan', 'products', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            $deliveryChallanData = $request->only('customer_id', 'delivery_order_id', 'delivery_date', 'delivery_address', 'vehicle_no');
            $deliveryChallanDetails = $request->products;
            DB::beginTransaction();
            $deliveryChallan = DeliveryChallan::find($id);
            $deliveryChallan->update($deliveryChallanData);
            $oldDeliveryChallanDetails = $deliveryChallan->deliveryChallanDetails()->get();
            $deliveryChallan->deliveryChallanDetails()->delete();
            $deliveryChallan->deliveryChallanDetails()->createMany($deliveryChallanDetails);
            foreach ($deliveryChallanDetails as $key => $deliveryChallanDetail) {
                $doDetalis = DeliveryOrderDetail::where('product_id', $deliveryChallanDetail['product_id'])->where('delivery_order_id', $request->delivery_order_id)->first();
                $doDetalis->update([
                    'remaining_quantity' => ($doDetalis->remaining_quantity + $oldDeliveryChallanDetails[$key]->quantity) - $deliveryChallanDetail['quantity']
                ]);
            }
            $do = DeliveryOrder::find($request->delivery_order_id);
            if ($do->deliveryOrderDetails()->where('remaining_quantity', '>', 0)->count() == 0) {
                $do->update([
                    'status' => 1
                ]);
            } else {
                $do->update([
                    'status' => 0
                ]);
            }
            DB::commit();
            return redirect()->route('delivery-challans.index')->with('message', 'Delivery Challan updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $deliveryChallan = DeliveryChallan::find($id);
            $deliveryChallan->deliveryChallanDetails()->delete();
            $deliveryChallan->delete();
            return redirect()->route('delivery-challans.index')->with('message', 'Delivery Challan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $deliveryChallan = DeliveryChallan::with('deliveryChallanDetails.product', 'salesOrder', 'customer',   'deliveryChallanDetails.measuringUnit')->find($id);
        // dd($deliveryChallan);
        $pdf = PDF::loadView('sales::delivery-challan.print', compact('deliveryChallan'),  [], [
            'title' => 'Delivery Challan',
            'format' => 'A4-L',
            'mode' => '',
        ]);
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('Delivery-Challan.pdf');
    }

    public function invoice($id)
    {
        $deliveryChallan = DeliveryChallan::with('deliveryChallanDetails.product', 'deliveryOrder.deliveryOrderDetails', 'customer',   'deliveryChallanDetails.measuringUnit')->find($id);

        $pdf = PDF::loadView('sales::delivery-challan.invoice', compact('deliveryChallan'),  [], [
            'title' => 'Delivery Challan',
            'format' => 'A4-P',
            'mode' => '',
        ]);
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('Delivery-Challan.pdf');
    }

    public function getDeliveryChallanDetails($id)
    {
        $deliveryOrder = DeliveryChallan::findOrFail($id)->load('deliveryChallanDetails.product', 'deliveryChallanDetails.measuringUnit', 'customer');
        return response()->json($deliveryOrder);
    }
}
