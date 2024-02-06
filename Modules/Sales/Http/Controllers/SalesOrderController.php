<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Bank;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\Customer;
use Modules\Sales\Entities\SalesOrder;
use Modules\SupplyChain\Entities\Product;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ProductSize;
use Modules\SupplyChain\Entities\ProductType;
use PDF;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $salesOrders = SalesOrder::with('customer', 'salesOrderDetails.size')->latest()->get();
        return view('sales::sales-order.index', compact('salesOrders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        return view('sales::sales-order.create', compact('formType', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $salesOrder = $request->only('date', 'customer_id', 'reference_employee_id');
            $salesOrderDetails = $request->raw_materials;
            DB::beginTransaction();
            $salesOrder = SalesOrder::create($salesOrder);
            $salesOrder->salesOrderDetails()->createMany($salesOrderDetails);
            DB::commit();
            return redirect()->route('sales-orders.index')->with('message', 'Sales Order Created Successfully');
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
        $salesOrder = SalesOrder::with('customer', 'salesOrderDetails.product.productType')->findOrFail($id);
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $productType = ProductType::orderBy('name')->pluck('name', 'id');
        $allProducts = Product::orderBy('name')->pluck('name', 'id');
        $sizes = ProductSize::orderBy('name')->pluck('name', 'id');
        return view('sales::sales-order.create', compact('formType', 'salesOrder', 'customers', 'productType', 'allProducts', 'sizes'));
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
            $salesOrderData = $request->only('date', 'customer_id', 'reference_employee_id');
            $salesOrderDetails = $request->raw_materials;
            DB::beginTransaction();
            $salesOrder = SalesOrder::findOrFail($id);
            $salesOrder->update($salesOrderData);
            $salesOrder->salesOrderDetails()->delete();
            $salesOrder->salesOrderDetails()->createMany($salesOrderDetails);
            DB::commit();
            return redirect()->route('sales-orders.index')->with('message', 'Sales Order Updated Successfully');
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
            $salesOrder = SalesOrder::findOrFail($id);
            $salesOrder->salesOrderDetails()->delete();
            $salesOrder->delete();
            return redirect()->route('sales-orders.index')->with('message', 'Sales Order Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $salesOrder = SalesOrder::with('customer', 'salesOrderDetails.product')->findOrFail($id);
        // dd($salesOrder);
        $pdf = PDF::loadView('sales::sales-order.print', compact('salesOrder'),  [], [
            'title' => 'Sales Order',
            'format' => 'A4-P',
            'mode' => '',
        ]);
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('Sales-order.pdf');
    }
}
