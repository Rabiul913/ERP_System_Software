<?php

namespace Modules\Sales\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\SalesReturn;
use Modules\Sales\Entities\DeliveryChallan;
use Modules\SupplyChain\Entities\Warehouse;
use Illuminate\Contracts\Support\Renderable;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $sales_return = SalesReturn::with('sales_return_details', 'delivery_challan')->orderBy('id', 'desc')->get();

        return view('sales::sales-return.index', compact('sales_return'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        // dd($formType);
        $warehouses = Warehouse::orderBy('name')->pluck('name', 'id');
        // $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        // $allProducts = Product::orderBy('name')->pluck('name', 'id');
        $deliveryChallans = DeliveryChallan::orderBy('id', 'desc')->pluck('id', 'id')->map(function ($item, $key) {
            return 'DCN-' . $item;
        });
        return view('sales::sales-return.create', compact('formType', 'deliveryChallans','warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $sales_return = $request->only('delivery_challan_id', 'return_date', 'warehouse_id', 'reason');
            $sale_return_details = $request->sales_return_details;
            DB::beginTransaction();
            $saleReturn = SalesReturn::create($sales_return);
            $saleReturn->sales_return_details()->createMany($sale_return_details);

//             $saleReturn->stocks()->create([
//                 'product_id' => $sale_return_details['product_id'],
//                 'batch_id' => $productpurchaserecieve->id,
//                 'quantity' => $value['received_qty'],
//                 'unit_price' => $value['unit_price'],
//                 'unit' => '',
//                 'remaining_quantity' => $value['received_qty'],
//                 'warehouse_id' => $productpurchaserecieve->warehouse_id,
//              ]);
            DB::commit();

            return redirect()->route('sales-returns.index')->with('message', 'Sales Return Created Successfully');
        } catch (Exception $e) {
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
        $sales_return = SalesReturn::findOrFail($id);
        $warehouses = Warehouse::orderBy('name')->pluck('name', 'id');
        $deliveryChallans = DeliveryChallan::orderBy('id', 'desc')->pluck('id', 'id')->map(function ($item, $key) {
            return 'DCN-' . $item;
        });
        return view('sales::sales-return.create', compact('formType', 'deliveryChallans', 'sales_return','warehouses'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $deliveryChallan = SalesReturn::find($id);
            $deliveryChallan->sales_return_details()->delete();
            $deliveryChallan->delete();
            return redirect()->route('sales-returns.index')->with('message', 'Sales Return deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
