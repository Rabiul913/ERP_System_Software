<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Entities\LeaveType;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $formType = 'create';
        $leavetypes = LeaveType::latest()->get();
        return view('hr::leave-type.index', compact('formType', 'leavetypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::leave-type.create', compact('formType'));
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
                LeaveType::create($input);
            });
            return redirect()->route('leave-types.index')->with('success','Leave Type Created Successfully');
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
        $leavetype = LeaveType::findOrFail($id);
        return view('hr::leave-type.create', compact('formType', 'leavetype'));
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
                LeaveType::findOrFail($id)->update($input);
            });
            return redirect()->route('leave-types.index')->with('success','Leave Type Updated Successfully');
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
        try {
            $message = 0;
            DB::transaction(function () use ($id, &$message) {
                $leavetype = LeaveType::with('leave_balance_details','leave_entry')->findOrFail($id);
                // dd($leavetype);
                if ($leavetype->leave_entry->count() === 0) {
                    $leavetype->delete();          
                    $message = ['message'=>'Leave type deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('leave-types.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('leave-types.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
