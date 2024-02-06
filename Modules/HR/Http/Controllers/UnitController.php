<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Unit;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $units = Unit::latest()->get();
        return view('hr::unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::unit.create', compact('formType'));
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
                Unit::create($input);
            });
            return redirect()->route('units.index')->with('message', 'Unit created successfully.');
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
        return view('hr::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $formType = 'edit';
        $unit = Unit::findOrFail($id);
        return view('hr::unit.create', compact('formType', 'unit'));
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
                $unit = Unit::findOrFail($id);
                $unit->update($input);
            });
            return redirect()->route('units.index')->with('message', 'Unit updated successfully.');
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
            $message = 0;
            DB::transaction(function () use ($id, &$message) {
                $unit = Unit::with('employees')->findOrFail($id);
                // dd($unit);
                if ($unit->employees->count() === 0) {
                    $unit->delete();          
                    $message = ['message'=>'Unit deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('units.index')->with($message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
