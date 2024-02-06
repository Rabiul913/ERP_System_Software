<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\ProcessedSalary;

class ProcessedSalaryController extends Controller
{
      /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $processed_salaries = ProcessedSalary::with('employee')->latest()->get();
        $departments = Department::where('status', 'Active')->pluck('name', 'id');
        return view('hr::salary.index_processed', compact('processed_salaries','departments'));
    }

    public function processedSalary(Request $request)
    {
        $com_user_id=Auth::user()->com_id;
        
        try{
            DB::select("SET @month = ?, @Department = ?, @Com_User = ?", [$request->month, $request->department_id,$com_user_id]);
            DB::select("CALL salary_processed(@month,@Department,@Com_User)");

            return redirect()->back()->with('message','Salary processed created successfully');        
        
        } catch(QueryException $e){
            return redirect()->back()->with('error',$e);          
        }
    }
}
