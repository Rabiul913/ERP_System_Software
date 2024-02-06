<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Employee;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sales\Entities\SalesZone;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('sales::customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        return view('sales::customer.create', compact('formType', 'employees', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            DB::transaction(function () use ($input) {
                Customer::create($input);
            });

            return redirect()->route('customers.index')->with('message', 'Customer Created Successfully');
        } catch (\Exception $e) {
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

        try {
            $customer = Customer::findOrFail($id);
            $formType = 'edit';
            $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
            $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');

            return view('sales::customer.create', compact('customer', 'formType', 'employees', 'zones'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
            $input = $request->all();
            DB::transaction(function () use ($input, $id) {
                $customer = Customer::findOrFail($id);
                $customer->update($input);
            });

            return redirect()->route('customers.index')->with('message', 'Customer Updated Successfully');
        } catch (\Exception $e) {
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
            DB::transaction(function () use ($id) {
                $customer = Customer::findOrFail($id);
                $customer->delete();
            });

            return redirect()->route('customers.index')->with('message', 'Customer Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getCustomerDetails(Request $request)
    {
        $customer = Customer::with('employee')->findOrFail($request->customer_id);
        return response()->json($customer);
    }
}
