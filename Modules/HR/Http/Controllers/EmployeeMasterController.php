<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Bank;
use Modules\HR\Entities\Line;
use Modules\HR\Entities\Unit;
use Modules\HR\Entities\Floor;
use Modules\HR\Entities\Grade;
use Modules\HR\Entities\Shift;
use Modules\HR\Entities\Gender;
use Modules\HR\Entities\PayMode;
use Modules\HR\Entities\Section;
use Modules\HR\Entities\District;
use Modules\HR\Entities\Division;
use Modules\HR\Entities\Religion;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\SubSection;
use Modules\HR\Entities\Designation;
use Modules\HR\Entities\EmployeeType;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\HR\Entities\Employee;

class EmployeeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->get();
        return view('hr::employee-master.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $department = Department::orderBy('name')->pluck('name', 'id');
        $section = Section::orderBy('name')->pluck('name', 'id');
        $sub_section = SubSection::orderBy('name')->pluck('name', 'id');
        $designation = Designation::orderBy('name')->pluck('name', 'id');
        $unit = Unit::orderBy('name')->pluck('name', 'id');
        $floor = Floor::orderBy('name')->pluck('name', 'id');
        $line = Line::orderBy('name')->pluck('name', 'id');
        $employeeType = EmployeeType::orderBy('name')->pluck('name', 'id');
        $shift = Shift::orderBy('name')->pluck('name', 'id');
        $gender = Gender::orderBy('name')->pluck('name', 'id');
        $religion = Religion::orderBy('name')->pluck('name', 'id');
        $grade = Grade::orderBy('name')->pluck('name', 'id');
        $division = Division::orderBy('name')->pluck('name', 'id');
        $paymode = PayMode::orderBy('name')->pluck('name', 'id');
        $bank = Bank::orderBy('name')->pluck('name', 'id');

        return view('hr::employee-master.create', compact(
            'formType',
            'department',
            'section',
            'sub_section',
            'designation',
            'unit',
            'floor',
            'line',
            'employeeType',
            'shift',
            'gender',
            'religion',
            'grade',
            'division',
            'paymode',
            'bank'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request);
        
        $employee = $request->except(
            '_token',
            'employee_detail',
            'employee_address',
            'employee_bank_info',
            'employee_family_info',
            'employee_nominee_info',
            'employee_education',
            'employee_experience'
        );
        // dd($employee);
        $employee_detail = $request->employee_detail;
        $employee_address = $request->employee_address;
        $employee_bank_info = $request->employee_bank_info;
        $employee_family_info = $request->employee_family_info;
        $employee_nominee_info = $request->employee_nominee_info;
        $employee_educations = $request->employee_education;
        $employee_experience = $request->employee_experience;
        try {

            DB::transaction(function () use (
                $employee,
                $employee_detail,
                $employee_address,
                $employee_bank_info,
                $employee_family_info,
                $employee_nominee_info,
                $employee_educations,
                $employee_experience
            ) {
                $employee = Employee::create($employee);
                $employee->employee_detail()->create($employee_detail);
                $employee->employee_address()->createMany($employee_address);
                $employee->employee_bank_info()->create($employee_bank_info);
                $employee->employee_family_info()->create($employee_family_info);
                $employee->employee_nominee_info()->createMany($employee_nominee_info);
                $employee->employee_education()->createMany($employee_educations);
                if(!empty($employee_experience)){
                    $employee->employee_experience()->createMany($employee_experience);
                }
            });

            return redirect()->route('employee-masters.index')->with('message', 'Employee created successfully.');
        } catch (QueryException $e) {
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

        $employee = Employee::findOrFail($id)->load(
            'employee_address',
            'employee_bank_info',
            'employee_detail',
            'employee_education',
            'employee_experience',
            'employee_family_info',
            'employee_nominee_info'
        );

        $department = Department::orderBy('name')->pluck('name', 'id');
        $section = Section::orderBy('name')->pluck('name', 'id');
        $sub_section = SubSection::orderBy('name')->pluck('name', 'id');
        $designation = Designation::orderBy('name')->pluck('name', 'id');
        $unit = Unit::orderBy('name')->pluck('name', 'id');
        $floor = Floor::orderBy('name')->pluck('name', 'id');
        $line = Line::orderBy('name')->pluck('name', 'id');
        $employeeType = EmployeeType::orderBy('name')->pluck('name', 'id');
        $shift = Shift::orderBy('name')->pluck('name', 'id');
        $gender = Gender::orderBy('name')->pluck('name', 'id');
        $religion = Religion::orderBy('name')->pluck('name', 'id');
        $grade = Grade::orderBy('name')->pluck('name', 'id');
        $division = Division::orderBy('name')->pluck('name', 'id');
        $district = District::orderBy('name')->pluck('name', 'id');
        $paymode = PayMode::orderBy('name')->pluck('name', 'id');
        $bank = Bank::orderBy('name')->pluck('name', 'id');

        return view('hr::employee-master.create', compact(
            'formType',
            'department',
            'section',
            'sub_section',
            'designation',
            'unit',
            'floor',
            'line',
            'employeeType',
            'shift',
            'gender',
            'religion',
            'grade',
            'division',
            'district',
            'paymode',
            'bank',
            'employee'
        ));
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
        $employee_info = Employee::findOrFail($id);
        $employee = $request->except(
            '_token',
            'employee_detail',
            'employee_address',
            'employee_bank_info',
            'employee_family_info',
            'employee_nominee_info',
            'employee_education',
            'employee_experience'
        );
        $employee_detail = $request->employee_detail;
        $employee_address = $request->employee_address;
        $employee_bank_info = $request->employee_bank_info;
        $employee_family_info = $request->employee_family_info;
        $employee_nominee_info = $request->employee_nominee_info;
        $employee_educations = $request->employee_education;
        $employee_experience = $request->employee_experience;
        try {

            DB::transaction(function () use (
                $employee,
                $employee_info,
                $employee_detail,
                $employee_address,
                $employee_bank_info,
                $employee_family_info,
                $employee_nominee_info,
                $employee_educations,
                $employee_experience
            ) {
                $employee_info->update($employee);

                $employee_info->employee_detail()->delete();
                $employee_info->employee_detail()->create($employee_detail);

                $employee_info->employee_address()->delete();
                $employee_info->employee_address()->createMany($employee_address);

                $employee_info->employee_bank_info()->delete();
                $employee_info->employee_bank_info()->create($employee_bank_info);

                $employee_info->employee_family_info()->delete();
                $employee_info->employee_family_info()->create($employee_family_info);

                $employee_info->employee_nominee_info()->delete();
                $employee_info->employee_nominee_info()->createMany($employee_nominee_info);


                $employee_info->employee_education()->delete();
                $employee_info->employee_education()->createMany($employee_educations);

                $employee_info->employee_experience()->delete();
                $employee_info->employee_experience()->createMany($employee_experience);
            });

            return redirect()->route('employee-masters.index')->with('message', 'Employee Updated successfully.');
        } catch (QueryException $e) {
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
        // dd($id);
        try {
            $employee_info = Employee::findOrFail($id);
            // dd($employee_info);
            $employee_info->employee_detail()->delete();
            $employee_info->employee_address()->delete();
            $employee_info->employee_bank_info()->delete();
            $employee_info->employee_family_info()->delete();
            $employee_info->employee_nominee_info()->delete();
            $employee_info->employee_education()->delete();
            $employee_info->employee_experience()->delete();
            $employee_info->delete();
            
            return redirect()->route('employee-masters.index')->with('message', 'Employee Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
