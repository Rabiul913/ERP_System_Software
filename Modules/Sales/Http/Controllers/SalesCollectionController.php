<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Employee;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\SalesCollection;
use Modules\Sales\Entities\SalesOrder;

class SalesCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $salesCollections = SalesCollection::orderBy('date', 'desc')->get();
        return view('sales::sale-collection.index', compact('salesCollections'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::sale-collection.create', compact('formType', 'customers', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only('customer_id', 'employee_id', 'date', 'bank_name', 'cheque_no', 'amount', 'remark');
            DB::beginTransaction();
            SalesCollection::create($input);
            DB::commit();

            return redirect()->route('sales-collections.index')->with('message', 'Sales Collection created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales-collections.index')->with('error', 'Error! ' . $e->getMessage());
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
        $salesCollection = SalesCollection::findOrFail($id);
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::sale-collection.create', compact('formType', 'salesCollection', 'customers', 'employees'));
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
            $input = $request->only('customer_id', 'employee_id', 'date', 'bank_name', 'cheque_no', 'amount', 'remark');
            DB::beginTransaction();
            SalesCollection::where('id', $id)->update($input);
            DB::commit();

            return redirect()->route('sales-collections.index')->with('message', 'Sales Collection updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales-collections.index')->with('error', 'Error! ' . $e->getMessage());
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
            DB::beginTransaction();
            SalesCollection::where('id', $id)->delete();
            DB::commit();
            return redirect()->route('sales-collections.index')->with('message', 'Sales Collection deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales-collections.index')->with('error', 'Error! ' . $e->getMessage());
        }
    }

   
}
