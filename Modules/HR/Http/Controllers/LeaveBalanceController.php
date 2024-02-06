<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Entities\LeaveBalance;
use Modules\HR\Entities\LeaveBalanceDetail;
use Modules\HR\Entities\Employee;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\EmployeeType;
use Modules\HR\Entities\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $leave_balances = LeaveBalance::with('leave_balance_details','employee')->latest()->get();
        // dd($leave_balances);
        return view('hr::leave-balance.index', compact('leave_balances'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $departments = Department::where('status', 'Active')->pluck('name', 'id');
        $sections = Section::where('status', 'Active')->pluck('name', 'id');
        $employees = Employee::with('employee_salary')->where('is_active', 1)->pluck(DB::raw("CONCAT(emp_name, ' - ', emp_code) AS emp_name"),'id');

        return view('hr::leave-balance.create', compact('formType','departments','sections', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $message = ['message'=>'Leave Balance created successfully.'];

        try {
            $input = $request->except('_token');
            DB::beginTransaction();

            if(!empty($request->all_emp)){
                $employees= Employee::latest()->where('is_active', 1)->get();
                foreach($employees as $data){
                    $this->employee_leave_balance_insert();
                }
            } else if(!empty($request->emp_wise)){
                $leave_balance_emp=LeaveBalance::where('emp_id',$request->employee_id)->where('year',$request->year)->latest()->first();
                if(empty($leave_balance_emp)){
                    $leaveblance= [];
                    $leaveblance['emp_id']=$request->employee_id;
                    $leaveblance['year']=$request->year;
                    $leaveblance['com_user_id']=Auth::user()->id;
                    
                    $leaveblancedetail= [];
                    $leaveblancedetail['cl']=$request->cl;
                    $leaveblancedetail['sl']=$request->sl;
                    $leaveblancedetail['el']=$request->el;
                    $leaveblancedetail['ml']=$request->ml;
                    $leaveblancedetail['other']=$request->other;

                    $leaveblance = LeaveBalance::create($leaveblance);
                    $leaveblance->leave_balance_details()->create($leaveblancedetail);
                }else{
                    $message = ['error'=>'This employee data already inserted.'];
                }
            }else if(!empty($request->department)){
                $departments = Department::with('employees')->findOrFail($request->department_id);
                if($departments->employees->count()!==0){
                    foreach($departments->employees as $data){                        
                        $this->employee_leave_balance_insert();
                    }
                }
            }else if(!empty($request->section)){
                $sections = Section::with('employees')->findOrFail($request->section_id);
                if($sections->employees->count()!==0){
                    foreach($sections->employees as $data){
                        $this->employee_leave_balance_insert();
                    }
                }
            }
           
            DB::commit();
            return redirect()->route('leave-balances.index')->with($message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    // for leave balance insert
    public function employee_leave_balance_insert(){
        $leave_balance_emp=LeaveBalance::where('emp_id',$data->id)->where('year',$request->year)->latest()->first();
        if(empty($leave_balance_emp)){
            $leaveblance= [];
            $leaveblance['emp_id']=$data->id;
            $leaveblance['year']=$request->year;
            $leaveblance['com_user_id']=Auth::user()->id;
            
            $leaveblancedetail= [];
            $leaveblancedetail['cl']=$request->cl;
            $leaveblancedetail['sl']=$request->sl;
            $leaveblancedetail['el']=$request->el;
            $leaveblancedetail['ml']=$request->ml;
            $leaveblancedetail['other']=$request->other;

            $leaveblance = LeaveBalance::create($leaveblance);
            $leaveblance->leave_balance_details()->create($leaveblancedetail);
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
        $departments = Department::where('status', 'Active')->pluck('name', 'id');
        $sections = Section::where('status', 'Active')->pluck('name', 'id');
        $employees = Employee::with('employee_salary')->where('is_active', 1)->pluck(DB::raw("CONCAT(emp_name, ' - ', emp_code) AS emp_name"),'id');
        $leave_balance = LeaveBalance::with('leave_balance_details')->findOrFail($id);
        return view('hr::leave-balance.create', compact('formType','departments','sections', 'employees','leave_balance'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        try {
            $input = $request->except('_token');
            DB::beginTransaction();

            $leaveblancedata= [];
            $leaveblancedata['com_user_id']=Auth::user()->id;
            
            $leaveblancedetail= [];
            $leaveblancedetail['cl']=$request->cl;
            $leaveblancedetail['sl']=$request->sl;
            $leaveblancedetail['el']=$request->el;
            $leaveblancedetail['ml']=$request->ml;
            $leaveblancedetail['other']=$request->other;


            $leaveblance=LeaveBalance::findOrFail($id);
            // dd($leaveblance);
            $leaveblance->update($leaveblancedata);

            $leaveblance->leave_balance_details()->delete();
            $leaveblance->leave_balance_details()->create($leaveblancedetail);
            DB::commit();
            return redirect()->route('leave-balances.index')->with('message', 'Leave Balance Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        //
    }


}
