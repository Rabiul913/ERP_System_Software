<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use App\Rules\FromToDateRule;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Designation;
use Modules\HR\Entities\Employee;
use Modules\HR\Entities\EmployeeIncrement;
use Modules\HR\Entities\EmployeeType;
use Modules\HR\Entities\LeaveType;
use Modules\HR\Entities\ProcessedAttendance;
use Modules\HR\Entities\ProcessedSalary;
use Modules\HR\Entities\ReleasedType;
use Modules\HR\Entities\Shift;
use PDF;
use Illuminate\Validation\Rule;
use Modules\HR\Entities\Allowance;
use Modules\HR\Entities\Bonus;
use Modules\HR\Entities\FixAttendance;
use Modules\HR\Entities\LeaveBalance;
use Modules\HR\Entities\LeaveEntry;
use Modules\HR\Entities\ProcessedBonus;
use Modules\HR\Entities\ProcessedBonusDetail;

class HRReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('hr::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hr::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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
        return view('hr::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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

    public function employeeList(){
        $formType = 'employee-list';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $released_types = ReleasedType::orderBy('name')->get();
        return view('hr::employee-list-report.index', compact('formType', 'employee_types', 'designations', 'departments', 'released_types'));
    }

    public function employeeListReport(Request $request){
        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $status = $request->status;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Status" => $status != null ? ($status == "active" ? "Active" : ReleasedType::find($status)?->name) : 'All',
        ];

        $reportData = Employee::leftJoin('employee_types', 'employee_types.id', '=', 'employees.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'employees.shift_id')
                                ->select('employees.*')
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })
                                ->when($status, function ($query) use($status) {
                                    return $status == 'active' ? $query->where('employees.is_active', 1) :
                                    $query->join('employee_releases', 'employee_releases.employee_id', '=', 'employees.id')
                                            ->where('employee_releases.released_type_id', '=', $status)
                                            ->where('employees.is_active', '!=', 1);
                                })
                                ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                                    return $query->whereBetween('employees.join_date', [$from_date, $to_date]);
                                })
                                ->get();
        // dd($reportData);



        $pdf = PDF::loadView('hr::employee-list-report.print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Employee List Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('employee_list_report.pdf');
    }

    public function promotionIncrementList(){
        $formType = 'promotion-increment-list';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('hr::promotion-increment-list-report.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    public function promotionIncrementListReport(Request $request){
        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $employee_id = $request->employee_id;
        $department_id = $request->department_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $status = $request->status;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];

        $reportData = EmployeeIncrement::leftJoin('employees', 'employees.id', '=', 'employee_increments.employee_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'employees.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'employees.shift_id')
                                ->select('employee_increments.*')
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })
                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })
                                ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                                    return $query->whereBetween('employee_increments.date', [$from_date, $to_date]);
                                })
                                ->get();



        $pdf = PDF::loadView('hr::promotion-increment-list-report.print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Promotion Increment List Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('promotion_increment_list_report.pdf');
    }

    public function dailyAttendance(){
        $formType = 'daily-attendance-list';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $shifts = Shift::orderBy('name')->pluck('name', 'id');
        $leave_types = LeaveType::orderBy('id')->pluck('name', 'short_name');
        // dd($leave_types);

        return view('hr::daily-attendance-list-report.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees', 'shifts', 'leave_types'));
    }

    public function dailyAttendanceReport(Request $request){
        // dd($request);
        // $request->validate([
        //     'from_date' => 'required',
        //     'to_date' => ['required',  new FromToDateRule()]
        // ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $shift_id = $request->shift_id;
        $employee_id = $request->employee_id;
        $status = $request->status;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Shift" => $shift_id != null ? Shift::find($shift_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
            "Status" => $status != null ? ( $status == 'P' ? 'Present' : ( $status == 'A' ? 'Absent' : ( $status == 'L' ? 'Late' : LeaveType::where('short_name', $status)->first()?->name ) ) ) : 'All',
        ];

        $reportData = ProcessedAttendance::leftJoin('employees', 'employees.id', '=', 'processed_attendances.emp_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_attendances.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_attendances.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_attendances.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_attendances.shift_id')
                                ->select('processed_attendances.*')
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })
                                ->when($shift_id, function ($query) use($shift_id) {
                                    return $query->where('shifts.id', $shift_id);
                                })
                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })
                                ->when($status, function ($query) use($status) {
                                    return $query->whereRaw('LOWER(processed_attendances.status) = ?', [strtolower($status)]);
                                })
                                ->where('processed_attendances.punch_date', Carbon::now()->format('Y-m-d'))
                                ->get();

        $pdf = PDF::loadView('hr::daily-attendance-list-report.print',
            compact('reportData', 'print_date_time', 'search_criteria'),
            [],
            [
                'title' => 'Daily Attendance List Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('daily_attendance_list_report.pdf');
    }

    public function attendanceSummary(){
        $formType = 'attendance-summary';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        // $shifts = Shift::orderBy('name')->pluck('name', 'id');
        // $leave_types = LeaveType::orderBy('id')->pluck('name', 'short_name');
        // dd($leave_types);

        return view('hr::attendance-summary.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    public function attendanceSummaryReport(Request $request){
        $request->validate([
            'month' => 'required_without:year',
            'year' => 'required_without:month',
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $month = $request->report_type == "monthly" ? $request->month : null;
        $year = $request->report_type == "yearly" ? $request->year : null;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];


        $reportData = ProcessedAttendance::leftJoin('employees', 'employees.id', '=', 'processed_attendances.emp_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_attendances.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_attendances.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_attendances.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_attendances.shift_id')
                                ->select('processed_attendances.emp_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type',
                                DB::raw('COUNT(CASE WHEN processed_attendances.status = "p" THEN 1 END) AS total_present'),
                                DB::raw('COUNT(CASE WHEN processed_attendances.status = "a" THEN 1 END) AS total_absent'),
                                DB::raw('COUNT(CASE WHEN processed_attendances.status = "l" THEN 1 END) AS total_late'),
                                DB::raw('COUNT(CASE WHEN processed_attendances.status = "h" THEN 1 END) AS total_holiday'),
                                DB::raw('COUNT(CASE WHEN processed_attendances.status = "w" THEN 1 END) AS total_weekend'),
                                DB::raw('COUNT(CASE WHEN processed_attendances.status NOT IN ("p", "a", "l", "h", "w") THEN 1 END) AS total_leave')
                                )
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })

                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })

                                ->when($month, function ($query) use($month) {
                                    // return $query->where('employees.id', $employee_id);
                                    return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y-%m') = ?", $month);
                                })
                                ->when($year, function ($query) use($year) {
                                    // return $query->where('employees.id', $employee_id);
                                    return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y') = ?", $year);
                                })
                                // ->where('processed_attendances.punch_date', Carbon::now()->format('Y-m-d'))
                                ->groupBy('processed_attendances.emp_id', 'departments.name', 'designations.name', 'employee_types.name')
                                ->get();
        // dd($reportData);

        $pdf = PDF::loadView('hr::attendance-summary.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'month', 'year'),
            [],
            [
                'title' => 'Attendance Summary Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('attendance_summary_report.pdf');
    }

    public function jobCard(){
        $formType = 'job-card';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');

        return view('hr::job-card.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    public function jobCardReport(Request $request){
        $request->validate([
            'month' => 'required',
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $month = $request->month;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];


        $reportData = ProcessedAttendance::leftJoin('employees', 'employees.id', '=', 'processed_attendances.emp_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_attendances.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_attendances.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_attendances.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_attendances.shift_id')
                                ->select('processed_attendances.*', 'processed_attendances.emp_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type'
                                )
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })

                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })

                                ->when($month, function ($query) use($month) {
                                    return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y-%m') = ?", $month);
                                })

                                // ->where('processed_attendances.punch_date', Carbon::now()->format('Y-m-d'))
                                // ->groupBy('processed_attendances.emp_id', 'departments.name', 'designations.name', 'employee_types.name')
                                ->orderBy('processed_attendances.emp_id')
                                ->orderBy('processed_attendances.punch_date')
                                ->get()
                                ->mapToGroups(function($item){
                                    return [$item->emp_id => $item];
                                });
        // dd($reportData);

        $pdf = PDF::loadView('hr::job-card.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'month'),
            [],
            [
                'title' => 'Job Card',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('job_card.pdf');
    }

    public function leaveReportIndex(){
        $formType = 'leave-report';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        // $leave_types = LeaveType::orderBy('name')->pluck('name', 'id');


        return view('hr::leave-report.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    private function leaveCount($from_date, $to_date){

    }

    public function leaveReport(Request $request){
        $request->validate([
            'report_type' => ['required', Rule::in(['monthly', 'yearly'])],
            'month' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'monthly';
            }),
            'year' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'yearly';
            }),
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $month = $request->month;
        $month = $request->report_type == "monthly" ? $request->month : null;
        $year = $request->report_type == "yearly" ? $request->year : null;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');


        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];


        // $reportData = ProcessedAttendance::leftJoin('employees', 'employees.id', '=',       'processed_attendances.emp_id')
        //                         ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_attendances.employee_type_id')
        //                         ->leftJoin('departments', 'departments.id', '=', 'processed_attendances.department_id')
        //                         ->leftJoin('designations', 'designations.id', '=', 'processed_attendances.designation_id')
        //                         ->leftJoin('shifts', 'shifts.id', '=', 'processed_attendances.shift_id')
        //                         ->select('processed_attendances.*', 'processed_attendances.emp_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type'
        //                         )
        //                         ->when($employee_type_id, function ($query) use($employee_type_id) {
        //                             return $query->where('employee_types.id', $employee_type_id);
        //                         })
        //                         ->when($designation_id, function ($query) use($designation_id) {
        //                             return $query->where('designations.id', $designation_id);
        //                         })
        //                         ->when($department_id, function ($query) use($department_id) {
        //                             return $query->where('departments.id', $department_id);
        //                         })

        //                         ->when($employee_id, function ($query) use($employee_id) {
        //                             return $query->where('employees.id', $employee_id);
        //                         })

        //                         ->when($month, function ($query) use($month) {
        //                             // return $query->where('employees.id', $employee_id);
        //                             return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y-%m') = ?", $month);
        //                         })
        //                         ->when($year, function ($query) use($year) {
        //                             // return $query->where('employees.id', $employee_id);
        //                             return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y') = ?", $year);
        //                         })

        //                         // ->where('processed_attendances.punch_date', Carbon::now()->format('Y-m-d'))
        //                         // ->groupBy('processed_attendances.emp_id', 'departments.name', 'designations.name', 'employee_types.name')
        //                         ->orderBy('processed_attendances.emp_id')
        //                         ->orderBy('processed_attendances.punch_date')
        //                         ->get()
        //                         ->mapToGroups(function($item){
        //                             return [$item->emp_id => $item];
        //                         });
        // dd($reportData);
        if($month != null){

            $year = date("Y", strtotime($month."-1"));
            $leave_balance = LeaveBalance::leftJoin('leave_balance_details', 'leave_balance_details.leave_balance_id', '=', 'leave_balances.id')
                                            ->orderBy('leave_balances.emp_id')
                                            ->get()->mapToGroups(function($item){
                                                // dd($item);
                                                return [$item->emp_id =>  $item];
                                            })->map(function($item2){
                                                return [
                                                        'cl' => $item2->first()->cl,
                                                        'sl' => $item2->first()->sl,
                                                        'el' => $item2->first()->el,
                                                        'ml' => $item2->first()->ml,
                                                        'other' => $item2->first()->other,
                                                        ];

                                            });


            // $leave_balance = LeaveBalance::leftJoin('leave_balance_details', 'leave_balance_details.leave_balance_id', '=', 'leave_balances.id')
            //                                 ->orderBy('leave_balances.emp_id')
            //                                 ->get()->mapToGroups(function($item){
            //                                     // dd($item);
            //                                     return [$item->emp_id =>  $item];
            //                                 })->map(function($item2){
            //                                     return [
            //                                             'cl' => $item2->first()->cl,
            //                                             'sl' => $item2->first()->sl,
            //                                             'el' => $item2->first()->el,
            //                                             'ml' => $item2->first()->ml,
            //                                             'other' => $item2->first()->other,
            //                                             ];

            //                                 });


            $reportData = LeaveEntry::select([ 'employees.id as emp_id', 'leave_entries.leave_type_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type', 'leave_types.short_name', DB::raw('SUM(CASE
                                            WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") >= leave_entries.from_date
                                                        AND LAST_DAY(CONCAT(?, "-01")) <= leave_entries.to_date
                                                    THEN (DATEDIFF(leave_entries.to_date, leave_entries.from_date) + 1 -
                                                        CASE
                                                            WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.from_date
                                                            THEN DATEDIFF(DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d"), leave_entries.from_date)
                                                            ELSE 0
                                                        END -
                                                        CASE
                                                            WHEN LAST_DAY(CONCAT(?, "-01")) < leave_entries.to_date
                                                            THEN DATEDIFF(leave_entries.to_date, LAST_DAY(CONCAT(?, "-01")))
                                                            ELSE 0
                                                        END)
                                                    WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") < leave_entries.from_date
                                                        AND LAST_DAY(CONCAT(?, "-01")) > leave_entries.to_date
                                                    THEN DATEDIFF(leave_entries.to_date, leave_entries.from_date) + 1
                                                    WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.from_date
                                                        AND ? = DATE_FORMAT(leave_entries.to_date, "%Y-%m")
                                                    THEN DATEDIFF(leave_entries.to_date, leave_entries.from_date) + 1 -
                                                        CASE
                                                            WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.from_date
                                                            THEN DATEDIFF(DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d"), leave_entries.from_date)
                                                            ELSE 0
                                                        END
                                                    WHEN ? = DATE_FORMAT(leave_entries.from_date, "%Y-%m")
                                                        AND LAST_DAY(CONCAT(?, "-01")) < leave_entries.to_date
                                                    THEN DATEDIFF(leave_entries.to_date, leave_entries.from_date) + 1 -
                                                        CASE
                                                            WHEN LAST_DAY(CONCAT(?, "-01")) < leave_entries.to_date
                                                            THEN DATEDIFF(leave_entries.to_date, LAST_DAY(CONCAT(?, "-01")))
                                                            ELSE 0
                                                        END
                                                    ELSE 0
                                                END) AS total_days'),
                                                DB::raw('SUM(CASE
                                                    WHEN ? = DATE_FORMAT(leave_entries.from_date, "%Y-%m")
                                                    THEN 0
                                                    WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.from_date
                                                        AND DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.to_date
                                                    THEN DATEDIFF(leave_entries.to_date, leave_entries.from_date) + 1
                                                    WHEN DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") > leave_entries.from_date
                                                        AND DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d") < leave_entries.to_date
                                                    THEN DATEDIFF(DATE_FORMAT(CONCAT(?, "-01"), "%Y-%m-%d"), leave_entries.from_date)
                                                    ELSE 0
                                                END) AS prev_tot'),
                                            ])
                                            ->setBindings([
                                                $month, $month, $month, $month, $month, $month,
                                                $month, $month, $month, $month, $month, $month,
                                                $month, $month, $month, $month, $month, $month,
                                                $month, $month, $month, $month,

                                            ])
                                            ->rightJoin('employees', 'employees.id', '=', 'leave_entries.emp_id')
                                            ->leftJoin('employee_types', 'employee_types.id', '=', 'employees.employee_type_id')
                                            ->leftJoin('leave_types', 'leave_types.id', '=', 'leave_entries.leave_type_id')
                                            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                                            ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
                                            ->leftJoin('shifts', 'shifts.id', '=', 'employees.shift_id')
                                            ->when($employee_type_id, function ($query) use($employee_type_id) {
                                                return $query->where('employee_types.id', $employee_type_id);
                                            })
                                            ->when($designation_id, function ($query) use($designation_id) {
                                                return $query->where('designations.id', $designation_id);
                                            })
                                            ->when($department_id, function ($query) use($department_id) {
                                                return $query->where('departments.id', $department_id);
                                            })

                                            ->when($employee_id, function ($query) use($employee_id) {
                                                return $query->where('employees.id', $employee_id);
                                            })

                                            // ->when($year, function ($query) use($year) {
                                            //     return $query->where("leave_balances.year", $year);
                                            // })
                                            ->groupBy('employees.id', 'leave_entries.leave_type_id','employees.emp_name', 'employees.emp_code', 'departments.name', 'designations.name', 'employee_types.name')
                                            ->get()->mapToGroups(function($item)use($year){
                                                return [$item->emp_id => $item];
                                            })->map(function($item2)use($year){
                                                return ["emp_name"=>$item2->first()["emp_name"], "emp_code"=>$item2->first()["emp_code"], "department_name"=>$item2->first()["department_name"], "designation"=>$item2->first()["designation"], "type"=>$item2->first()["type"], "leave_type_wise" => $item2->mapToGroups(function($item3){
                                                    return [strtolower($item3->short_name) => $item3];
                                                }), "leave_balance" => LeaveBalance::leftJoin('leave_balance_details', 'leave_balance_details.leave_balance_id', '=', 'leave_balances.id')
                                                ->where('leave_balances.emp_id', $item2->first()["emp_id"])
                                                ->where('leave_balances.year', $year)
                                                ->orderBy('leave_balances.emp_id')
                                                ->get()->map(function($item3){
                                                    return [
                                                            'cl' => $item3->cl??0,
                                                            'sl' => $item3->sl??0,
                                                            'el' => $item3->el??0,
                                                            'ml' => $item3->ml??0,
                                                            'other' => $item3->other??0,
                                                            ];

                                                })];
                                                // $item2->mapToGroups(function($item3){
                                                //     return [strtolower($item3->short_name) => $item3];
                                                // });
                                            });
        // dd($reportData, $leave_balance);

        }
        else{
            $reportData = LeaveBalance::leftJoin('leave_balance_details', 'leave_balance_details.leave_balance_id', '=', 'leave_balances.id')
            ->leftJoin('employees', 'employees.id', '=', 'leave_balances.emp_id')
            ->leftJoin('employee_types', 'employee_types.id', '=', 'employees.employee_type_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
            ->leftJoin('shifts', 'shifts.id', '=', 'employees.shift_id')
            ->select('leave_balances.emp_id', 'leave_balance_details.*', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type'
            )
            ->when($employee_type_id, function ($query) use($employee_type_id) {
                return $query->where('employee_types.id', $employee_type_id);
            })
            ->when($designation_id, function ($query) use($designation_id) {
                return $query->where('designations.id', $designation_id);
            })
            ->when($department_id, function ($query) use($department_id) {
                return $query->where('departments.id', $department_id);
            })

            ->when($employee_id, function ($query) use($employee_id) {
                return $query->where('employees.id', $employee_id);
            })

            ->when($year, function ($query) use($year) {
                return $query->where("leave_balances.year", $year);
            })

            // ->where('processed_attendances.punch_date', Carbon::now()->format('Y-m-d'))
            // ->groupBy('processed_attendances.emp_id', 'departments.name', 'designations.name', 'employee_types.name')
            ->orderBy('leave_balances.emp_id')

            ->get();
        }

        $pdf = PDF::loadView('hr::leave-report.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'month', 'year'),
            [],
            [
                'title' => 'Leave Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('leave_report.pdf');
    }




    public function otSheet(){
        $formType = 'ot-sheet';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('hr::ot-sheet.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    public function otSheetReport(Request $request){
        $request->validate([
            'report_type' => ['required', Rule::in(['daily', 'monthly', 'yearly'])],
            'year' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'yearly';
            }),
            'month' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'monthly';
            }),

            'date' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'daily';
            }),
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $date = $request->report_type === 'daily' ? $request->date : null;
        $month = $request->report_type === 'monthly' ? $request->month : null;
        $year = $request->report_type === 'yearly' ? $request->year : null;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];


        $reportData = ProcessedAttendance::leftJoin('employees', 'employees.id', '=', 'processed_attendances.emp_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_attendances.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_attendances.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_attendances.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_attendances.shift_id')
                                ->select('processed_attendances.*', 'processed_attendances.emp_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type'
                                )
                                ->when($employee_type_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })

                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })

                                ->when($year, function ($query) use($year) {
                                    return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y') = ?", $year);
                                })

                                ->when($month, function ($query) use($month) {
                                    return $query->whereRaw("DATE_FORMAT(processed_attendances.punch_date, '%Y-%m') = ?", $month);
                                })



                                ->when($date, function ($query) use($date) {
                                    return $query->where("processed_attendances.punch_date", $date);
                                })
                                ->where('processed_attendances.ot_hour', '!=', '00:00:00')
                                ->orderBy('processed_attendances.emp_id')
                                ->orderBy('processed_attendances.punch_date')
                                ->get()
                                ->mapToGroups(function($item){
                                    return [$item->emp_id => $item];
                                })->map(function($item2){
                                    $total_ot_seconds = 0;
                                    foreach($item2 as $it){
                                        list($hours, $minutes, $seconds) = explode(':', $it->ot_hour);
                                        $total_ot_seconds += $hours * 3600 + $minutes * 60 + $seconds;
                                    }
                                    $totalOtHours = round($total_ot_seconds / 3600);
                                    return ['attendance_details' =>  $item2, 'total_ot_hours' => $totalOtHours];
                                });

        $pdf = PDF::loadView('hr::ot-sheet.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'year', 'month', 'date'),
            [],
            [
                'title' => 'Ot Sheet',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('ot_sheet.pdf');
    }

    public function salarySheet(){
        $formType = 'salary-sheet';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('hr::salary-sheet.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees'));
    }

    public function salarySheetReport(Request $request){
        $request->validate([
            'month' => 'required',
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;

        $month = $request->month;

        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];


        $reportData = ProcessedSalary::leftJoin('employees', 'employees.id', '=', 'processed_salaries.emp_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_salaries.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_salaries.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_salaries.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_salaries.shift_id')
                                ->select('processed_salaries.*', 'processed_salaries.emp_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type', 'shifts.name as shift_name'
                                )
                                ->when($employee_type_id && !$employee_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id && !$employee_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id && !$employee_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })

                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })

                                ->when($month, function ($query) use($month) {
                                    return $query->where("processed_salaries.month", $month);
                                })

                                ->orderBy('processed_salaries.emp_id')
                                ->get();

        $pdf = PDF::loadView('hr::salary-sheet.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'month'),
            [],
            [
                'title' => 'Salary Sheet',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('salary_sheet.pdf');
    }

    public function bonusSheet(){
        $formType = 'bonus-sheet';
        $employee_types = EmployeeType::orderBy('name')->pluck('name', 'id');
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $bonuses = Bonus::orderBy('name')->pluck('name', 'id');
        return view('hr::bonus-sheet.index', compact('formType', 'employee_types', 'designations', 'departments', 'employees', 'bonuses'));
    }

    public function bonusSheetReport(Request $request){
        // dd($request);
        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);

        $employee_type_id = $request->employee_type_id;
        $designation_id = $request->designation_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $bonus_id = $request->bonus_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;

        // $month = $request->month;

        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Employee Type" => $employee_type_id != null ? EmployeeType::find($employee_type_id)?->name : 'All',
            "Designation" => $designation_id != null ? Designation::find($designation_id)?->name : 'All',
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
            "Bonus" => $bonus_id != null ? Bonus::find($bonus_id)?->name : 'All',
        ];


        $reportData = ProcessedBonus::rightJoin('processed_bonus_details', 'processed_bonus_details.processed_bonus_id', '=', 'processed_bonuses.id')
                                ->leftJoin('employees', 'employees.id', '=', 'processed_bonus_details.employee_id')
                                ->leftJoin('employee_types', 'employee_types.id', '=', 'processed_bonus_details.employee_type_id')
                                ->leftJoin('departments', 'departments.id', '=', 'processed_bonus_details.department_id')
                                ->leftJoin('designations', 'designations.id', '=', 'processed_bonus_details.designation_id')
                                ->leftJoin('shifts', 'shifts.id', '=', 'processed_bonus_details.shift_id')
                                ->leftJoin('bonuses', 'bonuses.id', '=', 'processed_bonuses.bonus_id')
                                ->select('processed_bonus_details.*', 'processed_bonus_details.employee_id as employee_id', 'employees.emp_name', 'employees.emp_code', 'departments.name as department_name', 'designations.name as designation', 'employee_types.name as type', 'shifts.name as shift_name', 'bonuses.name as bonus_name', 'processed_bonuses.date as bonus_process_date'
                                )
                                ->when($employee_type_id && !$employee_id, function ($query) use($employee_type_id) {
                                    return $query->where('employee_types.id', $employee_type_id);
                                })
                                ->when($designation_id && !$employee_id, function ($query) use($designation_id) {
                                    return $query->where('designations.id', $designation_id);
                                })
                                ->when($department_id && !$employee_id, function ($query) use($department_id) {
                                    return $query->where('departments.id', $department_id);
                                })

                                ->when($employee_id, function ($query) use($employee_id) {
                                    return $query->where('employees.id', $employee_id);
                                })

                                ->when($bonus_id, function ($query) use($bonus_id) {
                                    return $query->where('bonuses.id', $bonus_id);
                                })

                                ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                                    return $query->whereBetween('processed_bonuses.date', [$from_date, $to_date]);
                                })



                                // ->when($month, function ($query) use($month) {
                                //     return $query->where("processed_bonuses.month", $month);
                                // })

                                ->orderBy('processed_bonuses.id')
                                ->orderBy('processed_bonus_details.employee_id')
                                ->get();


        $pdf = PDF::loadView('hr::bonus-sheet.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'from_date', 'to_date'),
            [],
            [
                'title' => 'Bonus Sheet',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('bonus_sheet.pdf');
    }





    public function getDepartmentWiseEmployees(Request $request){
        $employees = Employee::where('department_id', $request->department_id)->get()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'text' => $employee->emp_name,
            ];
        })->toArray();
        return response()->json($employees);
    }

    public function getTypeWiseEmployees(Request $request){
        $employees = Employee::where('employee_type_id', $request->employee_type_id)->get()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'text' => $employee->emp_name,
            ];
        })->toArray();
        return response()->json($employees);
    }

    public function getDesignationWiseEmployees(Request $request){
        $employees = Employee::where('designation_id', $request->designation_id)->get()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'text' => $employee->emp_name,
            ];
        })->toArray();
        return response()->json($employees);
    }

    public function getShiftWiseEmployees(Request $request){
        $employees = Employee::leftJoin('processed_attendances', 'processed_attendances.emp_id', '=', 'employees.id')
                            ->where('processed_attendances.punch_date', '=', Carbon::now()->format('Y-m-d'))
                            ->where('processed_attendances.shift_id', '=', $request->shift_id)
                            ->select('employees.*')
                            ->get()->map(function ($employee) {
                                return [
                                    'id' => $employee->id,
                                    'text' => $employee->emp_name,
                                ];
                            })->toArray();
        return response()->json($employees);
    }


    public function allowanceReportIndex(){

        $formType = 'allowance-report';
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('hr::allowance-report.index', compact('formType','employees'));
    }

    public function allowanceReport(Request $request){

        // dd($request->input());
        $request->validate([
            'report_type' => ['required', Rule::in(['monthly', 'yearly'])],
            'month' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'monthly';
            }),
            'year' => Rule::requiredIf(function () use ($request) {
                return $request->report_type === 'yearly';
            }),
        ]);


        $employee_id = $request->employee_id;
        $month = $request->month;
        $month = $request->report_type == "monthly" ? $request->month : null;
        $year = $request->report_type == "yearly" ? $request->year : null;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');


        $search_criteria = [
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->emp_name : 'All',
        ];

        $allowances = Allowance::with('employee','allowance_type');

        $allowances->when($employee_id, function ($query) use($employee_id) {
            return $query->where('employee_id', $employee_id);
        });

        if($month != null){
           // dd( date("Y-m-d", strtotime($month."-1")));
            $month_with_year = date("Y-m-d", strtotime($month."-1"));
            $startingOfMonth = Carbon::createFromFormat('Y-m-d', $month_with_year)->startOfMonth();
            $endOfMonth = Carbon::createFromFormat('Y-m-d', $month_with_year)->endOfMonth();
            $allowances->whereDate('created_at', '>=',$startingOfMonth )->whereDate('created_at', '<=', $endOfMonth );

         }else{
            $year = date("Y-m-d", strtotime($year));
            $startingOfYear = Carbon::createFromFormat('Y-m-d', $year)->startOfYear();
            $endOfYear = Carbon::createFromFormat('Y-m-d', $year)->endOfYear();
            $allowances->whereDate('created_at', '>=',$startingOfYear )->whereDate('created_at', '<=', $endOfYear);
         }

         $reportData = $allowances->get();
         //dd($reportData);

        $pdf = PDF::loadView('hr::allowance-report.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'month', 'year'),
            [],
            [
                'title' => 'Leave Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('allowance_report.pdf');
    }

    public function fixAttendanceReportIndex(){

        $formType = 'fix-attendance-report';
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        return view('hr::fix-attendance-report.index', compact('formType','employees','departments'));
    }

    public function fixAttendanceReport(Request $request){

        //dd($request->input());
        // $request->validate([
        //     'report_type' => ['required', Rule::in(['monthly', 'yearly'])],
        //     'month' => Rule::requiredIf(function () use ($request) {
        //         return $request->report_type === 'monthly';
        //     }),
        //     'year' => Rule::requiredIf(function () use ($request) {
        //         return $request->report_type === 'yearly';
        //     }),
        // ]);


        $employee_id = $request->employee_id;
        $from = $request->from;
        $to = $request->to;
        // $month = $request->month;
        // $month = $request->report_type == "monthly" ? $request->month : null;
        // $year = $request->report_type == "yearly" ? $request->year : null;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');


        $search_criteria = [
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->emp_name : 'All',
        ];

        $fixAttendance = FixAttendance::with('employee');

        $fixAttendance->when($employee_id, function ($query) use($employee_id) {
            return $query->where('emp_id', $employee_id);
        });

        $fixAttendance->whereDate('punch_date', '>=',$from )->whereDate('punch_date', '<=', $to);


         $reportData = $fixAttendance->get();
         //dd($reportData);

        $pdf = PDF::loadView('hr::fix-attendance-report.print',
            compact('reportData', 'print_date_time', 'search_criteria', 'from', 'to'),
            [],
            [
                'title' => 'Fix Attendance Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('fix_attendance_report.pdf');
    }

    public function paySlipReportIndex(){
        $formType = 'pay-slip-report';
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        return view('hr::pay-slip-sheet.index', compact('formType','employees','departments'));
    }

    public function paySlipReport(Request $request)
    {
        // dd($request);
        $employee_id = $request->employee_id;
        $department_id = $request->department_id;
        $type = $request->type;
        $month = $request->month;
        $dateString = "2023-09";
        list($getyear, $getmonth) = explode("-", $dateString);        
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $getmonth, $getyear);

        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Department" => $department_id != null ? Department::find($department_id)?->name : 'All',
            "Employee" => $employee_id != null ? Employee::find($employee_id)?->name : 'All',
        ];
        
        if($type == 'salary'){
            $reportData = Employee::with('processed_salary','designation','department','section','shift')
                        ->join('processed_salaries', 'processed_salaries.emp_id', '=', 'employees.id')
                        ->when($department_id, function ($query) use($department_id) {
                            return $query->where('processed_salaries.department_id', $department_id);
                        })
                        ->when($employee_id, function ($query) use($employee_id) {
                            return $query->where('processed_salaries.emp_id', $employee_id);
                        })
                        ->when($month, function ($query) use($month) {
                            return $query->where('processed_salaries.month', $month);
                        })
                        ->select('employees.*')
                        ->orderBy('processed_salaries.emp_id')
                        ->get();
        }else{
            $reportData= Employee::with('processed_bonous','designation','department','section','shift')
            ->join('processed_bonus_details', 'processed_bonus_details.employee_id', '=', 'employees.id')
            ->join('processed_bonuses', 'processed_bonuses.id', '=', 'processed_bonus_details.processed_bonus_id')
            ->when($department_id, function ($query) use($department_id) {
                return $query->where('employees.department_id', $department_id);
            })
            ->when($employee_id, function ($query) use($employee_id) {
                return $query->where('processed_bonus_details.employee_id', $employee_id);
            })
            ->when($month, function ($query) use($month) {
                return $query->whereRaw("DATE_FORMAT(processed_bonuses.date, '%Y-%m') = ?", $month);
            })
            ->select('employees.*')
            ->groupBy('processed_bonus_details.employee_id')
            ->get();
        }

        $pdf = PDF::loadView('hr::pay-slip-sheet.print',
            compact('reportData','type','totalDays','print_date_time', 'search_criteria','month', 'dateString'),
            [],
            [
                'title' => 'Ot Sheet',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
       
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('pay_clip_sheet.pdf');
    }


}
