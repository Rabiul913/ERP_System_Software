<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\AllowanceType;

class AllowanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $allowanceTypes = AllowanceType::latest()->get();
        return view('hr::allowance-type.index', compact('allowanceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = "create";
        return view('hr::allowance-type.create',compact('formType'));
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
                AllowanceType::create($input);
            });

            return redirect()->route('allowance-types.index')->with('message', 'Allowance Type information created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('allowance-types.index')->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hr::allowance-type.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $formType = "edit";
        $allowanceType = AllowanceType::findOrFail($id);
        return view('hr::allowance-type.create',compact('formType','allowanceType'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        try {
            $input = $request->all();
            DB::transaction(function () use ($input, $request, $id) {
                $allowanceType = AllowanceType::findOrFail($id);
                $allowanceType->update($input);
            });

            return redirect()->route('allowance-types.index')->with('message', 'Allowance type information updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('allowance-types.edit')->withInput()->withErrors($e->getMessage());
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
                // $allowanceType = AllowanceType::with('allowances')->findOrFail($id);
                // if ($allowanceType->allowances->count() === 0 ) {
                //     $allowanceType->delete();        
                //     $message = ['message'=>'Allwance Type information deleted successfully.'];
                // } else {
                //     $message = ['error'=>'This data has some dependency.'];
                // }

                // Use bellow code untill allowance module is created
                AllowanceType::findOrFail($id)->delete();
                $message = ['message'=>'Allowance Type information deleted successfully.'];
            });
            
            return redirect()->route('allowance-types.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('allowance-types.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
