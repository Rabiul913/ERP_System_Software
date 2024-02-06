<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Grade;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Designation;
use Illuminate\Contracts\Support\Renderable;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $designations = Designation::with('grade')->latest()->get();
        // dd($designations[0]);
        return view('hr::designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $grades = Grade::orderBy('name')->pluck('name', 'id');
        return view('hr::designation.create', compact('formType', 'grades'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
      
        try{
            $input = $request->all();
            DB::transaction(function () use ($input) {
                Designation::create($input);
            });
            return redirect()->route('designations.index')->with('message', 'Designation created successfully.');
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
        $designation = Designation::findOrFail($id);
        $grades = Grade::orderBy('name')->pluck('name', 'id');
        return view('hr::designation.create', compact('formType', 'designation', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
       
        try{
            $input = $request->all();
            DB::transaction(function () use ($input, $id) {
                Designation::findOrFail($id)->update($input);
            });
            return redirect()->route('designations.index')->with('message', 'Designation updated successfully.');
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
        
        try{
            $message = 0;
            DB::transaction(function () use ($id, &$message) {
                $designation = Designation::with('employees')->findOrFail($id);
                if ($designation->employees->count() === 0) {
                    $designation->delete();                    
                    $message = ['message'=>'Designation deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            // dd($message);
            return redirect()->route('designations.index')->with($message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
