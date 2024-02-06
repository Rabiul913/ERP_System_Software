<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\EmployeeType;
use Modules\HR\Entities\SalarySetting;
use Illuminate\Contracts\Support\Renderable;

class SalarySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $salarySettings = SalarySetting::with('employeeType')->latest()->get();
        return view('hr::salary-setting.index', compact('salarySettings'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $employeeTypes = EmployeeType::orderBy('name')->pluck('name','id');
        return view('hr::salary-setting.create', compact('formType','employeeTypes'));
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
                SalarySetting::create($input);
            });
            return redirect()->route('salary-settings.index')->with('success','Salary Setting Created Successfully');
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
        $employeeTypes = EmployeeType::orderBy('name')->pluck('name','id');
        $salarySetting = SalarySetting::findOrFail($id);
        $formType = 'edit';
        return view('hr::salary-setting.create', compact('formType','employeeTypes','salarySetting'));
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
            DB::transaction(function() use($input,$id){
                SalarySetting::findOrFail($id)->update($input);
            });
            return redirect()->route('salary-settings.index')->with('success','Salary Setting Updated Successfully');
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
            DB::transaction(function() use($id){
                SalarySetting::findOrFail($id)->delete();
            });
            return redirect()->route('salary-settings.index')->with('success','Salary Setting Deleted Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error','Something went wrong');
        }
    }
}
