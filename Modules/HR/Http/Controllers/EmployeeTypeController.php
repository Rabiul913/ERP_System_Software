<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\EmployeeType;
use Illuminate\Contracts\Support\Renderable;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $employeeTypes = EmployeeType::latest()->get();
        return view('hr::employee-type.index', compact('employeeTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::employee-type.create', compact('formType'));
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
            DB::transaction(function() use($input){
                EmployeeType::create($input);
            });
            return redirect()->route('employee-types.index')->with('success','Employee Type Created Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error','Something went wrong');
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
        $employeeType = EmployeeType::findOrFail($id);
        return view('hr::employee-type.create', compact('formType', 'employeeType'));
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
            DB::transaction(function() use($input, $id){
                EmployeeType::findOrFail($id)->update($input);
            });
            return redirect()->route('employee-types.index')->with('success','Employee Type Updated Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error','Something went wrong');
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
                $employeeType = EmployeeType::with('salarySettings','employees')->findOrFail($id);
                // dd($employeeType);
                if ($employeeType->salarySettings->count() === 0 && $employeeType->employees->count() === 0) {
                    $employeeType->delete();          
                    $message = ['message'=>'Employee type deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('employee-types.index')->with($message);
        } catch(\Exception $e){
            return redirect()->back()->with('error','Something went wrong');
        }
    }
}
