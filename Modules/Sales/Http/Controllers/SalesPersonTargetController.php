<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\HR\Entities\Employee;
use Modules\Sales\Entities\SalesPersonTarget;

class SalesPersonTargetController extends Controller
{
    public function index()
    {
        $sales_person_target = SalesPersonTarget::latest()->get();
        // dd($sales_person_target);
        return view('sales::sales-person-target.index', compact('sales_person_target'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = "create";
        $employee = Employee::where('is_active', 1)->orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::sales-person-target.create', compact('formType', 'employee'));
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
            DB::transaction(function () use ($input, $request) {

                $target = $request->only('month');
                $details = $request->target_details;
                $salesPersonTarget = SalesPersonTarget::create($target);
                $salesPersonTarget->target_order_details()->createMany($details);
            });

            return redirect()->route('sales-person-targets.index')->with('message', 'Sales Person Target created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-person-targets.index')->withInput()->withErrors($e->getMessage());
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
        $formType = "edit";
        $sales_target = SalesPersonTarget::findOrFail($id);
        $employee = Employee::where('is_active', 1)->orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::sales-person-target.create', compact('formType','sales_target','employee'));
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
            $target = $request->only('month');
            $details = $request->target_details;
            DB::transaction(function () use ($input, $request, $id, $target, $details) {
                $salesPersonTarget = SalesPersonTarget::findOrFail($id);
                $salesPersonTarget->update($target);
                $salesPersonTarget->target_order_details()->delete();
                $salesPersonTarget->target_order_details()->createMany($details);

            });

            return redirect()->route('sales-person-targets.index')->with('message', 'Sales Person Target updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-person-targets.edit')->withInput()->withErrors($e->getMessage());
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
                $sales_zone = SalesPersonTarget::findOrFail($id);
                $sales_zone->delete();
            });

            return redirect()->route('sales-person-targets.index')->with('message', 'Sales Person Target deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-person-targets.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
