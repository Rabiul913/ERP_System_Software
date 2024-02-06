<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Section;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Department;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\SoftwareSettings\Entities\CompanyInfo;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $sections = Section::with('department')->latest()->get();
        return view('hr::section.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = "create";
        $depertments =  Department::orderBy('name')->pluck('name', 'id');
        return view('hr::section.create',compact('formType','depertments'));
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
                Section::create($input);
            });

            return redirect()->route('sections.index')->with('message', 'Section information created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sections.edit')->withInput()->withErrors($e->getMessage());
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
        $formType = "edit";
        $depertments =  Department::orderBy('name')->pluck('name', 'id');
        $section = Section::findOrFail($id);
        return view('hr::section.create',compact('formType','depertments','section'));
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
            DB::transaction(function () use ($input, $request,$id) {
                Section::findOrFail($id)->update($input);
            });

            return redirect()->route('sections.index')->with('message', 'Section information updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('sections.edit')->withInput()->withErrors($e->getMessage());
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
                $section = Section::with('subsections','employees')->findOrFail($id);
                // dd($section);
                if ($section->subsections->count() === 0 && $section->employees->count() === 0) {
                    $section->delete();          
                    $message = ['message'=>'Section deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('sections.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('sections.index')->withInput()->withErrors($e->getMessage());
        }
    }
}
