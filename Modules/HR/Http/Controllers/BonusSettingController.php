<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Bonus;
use Modules\HR\Entities\BonusSetting;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Employee;

class BonusSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $bonusSettings = BonusSetting::with('employee','bonus')->latest()->get();
        return view('hr::bonus-setting.index',compact('bonusSettings'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $bonuses = Bonus::pluck('name','id');
        $departments = Department::pluck('name','id');
        $employees = Employee::pluck('emp_name','id');
        return view('hr::bonus-setting.create',compact('bonuses','departments','formType','employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        try {
            $input = $request->except('department_id');
            $input['bonus_id'] = json_encode($input['bonus_id']);
            DB::transaction(function () use ($input, $request) {
                if(BonusSetting::where('employee_id',$input['employee_id'])->count()>0){
                    $bonusSettings = BonusSetting::where('employee_id',$input['employee_id'])->first();
                    $bonusSettings->update($input);
                }else{
                    BonusSetting::create($input);
                }
            });

            return redirect()->route('bonus-settings.index')->with('message', 'Bonus Setting information created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('bonus-settings.index')->withInput()->withErrors($e->getMessage());
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
        $bonusSetting = BonusSetting::find($id);
        $bonuses = Bonus::pluck('name','id');
        $departments = Department::pluck('name','id');
        $employees = Employee::pluck('emp_name','id');
        return view('hr::bonus-setting.create',compact('bonusSetting','bonuses','departments','formType','employees'));
        return view('hr::bonus-setting.create');
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

            $input = $request->except('department_id');
            $input['bonus_id'] = implode(',', $input['bonus_id']);
            DB::transaction(function () use ($input, $request, $id) {
                $bonusSettings = BonusSetting::findOrFail($id);
                $bonusSettings->update($input);
            });

            return redirect()->route('bonus-settings.index')->with('message', 'Bonus Setting Updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('bonus-settings.index')->withInput()->withErrors($e->getMessage());
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
                BonusSetting::findOrFail($id)->delete();
                $message = ['message'=>'Bonus Setting information deleted successfully.'];
            });

            return redirect()->route('bonus-settings.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('bonus-settings.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
