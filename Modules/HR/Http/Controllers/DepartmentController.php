<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Department;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $departments = Department::latest()->get();
        return view('hr::department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = "create";
        return view('hr::department.create', compact('formType'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            DB::transaction(function () use ($input, $request) {
                Department::create($input);
            });

            return redirect()->route('departments.index')->with('message', 'Department information created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('departments.edit')->withInput()->withErrors($e->getMessage());
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
        $formType  = "edit";
        $department = Department::findOrFail($id);
        return view('hr::department.create', compact('department', 'formType'));
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
            DB::transaction(function () use ($input, $request, $id) {
                $department = Department::findOrFail($id);
                $department->update($input);
            });

            return redirect()->route('departments.index')->with('message', 'Department information updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('departments.edit')->withInput()->withErrors($e->getMessage());
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
                $department = Department::with('employees')->findOrFail($id);
                if ($department->employees->count() === 0) {
                    $department->delete();
                    
                    $message = ['message'=>'Department information deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            // dd($message);
            return redirect()->route('departments.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('departments.edit')->withInput()->withErrors($e->getMessage());
        }
    }
}
