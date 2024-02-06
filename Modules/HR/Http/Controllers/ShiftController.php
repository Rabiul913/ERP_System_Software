<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Shift;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $shifts = Shift::latest()->get();
        return view('hr::shift.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::shift.create', compact('formType'));
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
            Shift::create($input);
        });
        return redirect()->route('shifts.index')->with('message', 'Shift created successfully.');
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
        $shift = Shift::findOrFail($id);
        return view('hr::shift.create', compact('formType', 'shift'));
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
            $input = $request->all();
            DB::transaction(function () use ($input, $id) {
                Shift::findOrFail($id)->update($input);
            });
            return redirect()->route('shifts.index')->with('message', 'Shift updated successfully.');
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
        
        try {
            $message = 0;
            DB::transaction(function () use ($id, &$message) {
                $shift = Shift::with('employees','employeeshiftentry')->findOrFail($id);
                if ($shift->employees->count() === 0 && $shift->employeeshiftentry->count() === 0) {
                    $shift->delete();          
                    $message = ['message'=>'Shift deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('shifts.index')->with($message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
