<?php

namespace Modules\Sales\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Modules\HR\Entities\Employee;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\Customer;
use Modules\Sales\Entities\SalesZone;
use Illuminate\Database\QueryException;
use Modules\Sales\Entities\DeliveryOrder;
use Modules\SupplyChain\Entities\Product;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\SupplyChain\Entities\MeasuringUnit;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $deliveryOrders = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer', 'deliveryChallans')->orderBy('id', 'desc')->get();
        // dd($deliveryOrders);
        return view('sales::delivery-order.index', compact('deliveryOrders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::delivery-order.create', compact('formType', 'customers', 'units', 'employees', 'zones'));
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
            $deliveryOrderData = $request->only('date', 'customer_id', 'zone_id', 'contact_no', 'address', 'employee_id', 'delivery_address', 'total', 'paid', 'due', 'delivery_method');
            $deliveryOrderDetails = $request->raw_materials;
            DB::beginTransaction();
            $deliveryOrder = DeliveryOrder::create($deliveryOrderData);
            $deliveryOrder->deliveryOrderDetails()->createMany($deliveryOrderDetails);
            DB::commit();
            return redirect()->route('delivery-orders.index')->with('message', 'Delivery Order Created Successfully');
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
        $deliveryOrder = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->findOrFail($id);
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');
        // $sizes = ProductSize::orderBy('name')->pluck('name', 'id');
        return view('sales::delivery-order.create', compact('formType', 'deliveryOrder', 'customers', 'zones', 'units', 'employees', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $deliveryOrderData = $request->only('date', 'customer_id', 'zone_id', 'contact_no', 'address', 'employee_id', 'delivery_address', 'total', 'paid', 'due', 'delivery_method');
            $deliveryOrderDetails = $request->raw_materials;
            DB::beginTransaction();
            $deliveryOrder = DeliveryOrder::findOrFail($id);
            $deliveryOrder->update($deliveryOrderData);
            $deliveryOrder->deliveryOrderDetails()->delete();
            $deliveryOrder->deliveryOrderDetails()->createMany($deliveryOrderDetails);
            DB::commit();
            return redirect()->route('delivery-orders.index')->with('message', 'Delivery Order Updated Successfully');
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
            $deliveryOrder = DeliveryOrder::findOrFail($id);
            $deliveryOrder->deliveryOrderDetails()->delete();
            $deliveryOrder->delete();
            return redirect()->route('delivery-orders.index')->with('message', 'Delivery Order Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getPreviousDue(Request $request)
    {

        return response()->json('200');
    }

    public function print($id)
    {
        $deliveryOrder = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->findOrFail($id);
        // dd($deliveryOrder);
        $fileName = 'DO-' . $deliveryOrder->id . '.pdf';
        $pdf = PDF::loadView('sales::delivery-order.print', compact('deliveryOrder'),  [], [
            'title' => 'General Purchase Order',
            'format' => 'A4-L',
            'mode' => '',
        ]);
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream($fileName);
    }

    public function printWithoutValue($id)
    {
        $deliveryOrder = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->findOrFail($id);
        $fileName = 'DO-' . $deliveryOrder->id . '.pdf';
        $pdf = PDF::loadView('sales::delivery-order.print-without-value', compact('deliveryOrder'),  [], [
            'title' => 'General Purchase Order',
            'format' => 'A5-P',
            'mode' => '',
        ]);
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream($fileName);
    }

    public function getDeliveryOrderDetails($id)
    {
        $deliveryOrder = DeliveryOrder::with('deliveryOrderDetails.product.unit', 'employee', 'customer',)->findOrFail($id);
        return response()->json($deliveryOrder);
    }

    public function complete($requisition_id)
    {
        try {
            DeliveryOrder::where('id', $requisition_id)->update(['status' => 1, 'completed_by' => auth()->id()]);
            return redirect()->route('delivery-orders.index')->with('message', 'Delivery Order Completed successfully');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
