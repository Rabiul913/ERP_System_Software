<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\SalesZone;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;

class SalesZoneController extends Controller
{
    public function index()
    {
        $sales_zone = SalesZone::latest()->get();
        return view('sales::sales-zone.index', compact('sales_zone'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = "create";
        return view('sales::sales-zone.create', compact('formType'));
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
                SalesZone::create($input);
            });

            return redirect()->route('sales-zones.index')->with('message', 'Sales Zone created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-zones.index')->withInput()->withErrors($e->getMessage());
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
        $sales_zone = SalesZone::findOrFail($id);
        return view('sales::sales-zone.create', compact('formType','sales_zone'));
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
            DB::transaction(function () use ($input, $request, $id) {
                $sales_zone = SalesZone::findOrFail($id);
                $sales_zone->update($input);
            });

            return redirect()->route('sales-zones.index')->with('message', 'Sales Zone updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-zones.edit')->withInput()->withErrors($e->getMessage());
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
                $sales_zone = SalesZone::findOrFail($id);
                $sales_zone->delete();
            });

            return redirect()->route('sales-zones.index')->with('message', 'Sales Zone deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sales-zones.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
