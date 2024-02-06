<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Entities\ProcessedAttendance;
use Modules\HR\Entities\FixAttendance;
use Modules\HR\Entities\Employee;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Designation;
use Modules\HR\Entities\Section;
use Modules\HR\Entities\Shift;
use Modules\HR\Entities\Holiday;
use Modules\SoftwareSettings\Entities\CompanyInfo;
use Carbon\Carbon;

class ProcessedAttendanceController extends Controller
{
  
    public function processedAttendances(Request $request)
    {
        $com_user_id=Auth::user()->com_id;
        // dd($com_user_id);

        $monthYear =explode("-", $request->month); 
        $startDate =null;
        $endDate =null;
        if(count($monthYear)==2){
            $startDate = Carbon::create($monthYear[0], $monthYear[1], 1);
            $endDate = $startDate->copy()->endOfMonth();
        }

        try{
            if(count($monthYear)==2){
                foreach (Carbon::parse($startDate)->daysUntil($endDate) as $yeardate) {
                    $dateYear =date('Y-m-d', strtotime($yeardate));
                    DB::select("SET @DATE = ?, @Department= ? , @Overtime= ?,@Com_User= ?", [$date,$request->department_id,date("H:i:s", strtotime("00:00:00") + $request->overtime * 3600),$com_user_id]);
                    DB::select("CALL attendance_processed(@DATE,@Department,@Overtime,@Com_User)");
                }
            }else{
                $date= date('Y-m-d', strtotime($request->date));           
                DB::select("SET @DATE = ?, @Department= ? , @Overtime= ? ,@Com_User= ?", [$date,$request->department_id,date("H:i:s", strtotime("00:00:00") + $request->overtime * 3600),$com_user_id]);
                DB::select("CALL attendance_processed(@DATE,@Department,@Overtime,@Com_User)");
            }
            return redirect()->back()->with('message','Attendance processed created successfully');
        
        } catch(QueryException $e){

            return redirect()->back()->with('error',$e);          
        }
    }
}
