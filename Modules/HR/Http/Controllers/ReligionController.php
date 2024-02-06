<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Religion;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;

class ReligionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $religions = Religion::latest()->get();
        return view('hr::religion.index',compact('religions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::religion.create',compact('formType'));
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
                Religion::create($input);
            });
            return redirect()->route('religions.index')->with('success','Religion Created Successfully');
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
        $religion = Religion::find($id);
        return view('hr::religion.create',compact('formType','religion'));
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
            DB::transaction(function() use($input,$id){
                Religion::find($id)->update($input);
            });
            return redirect()->route('religions.index')->with('success','Religion Updated Successfully');
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
        try{
            $message = 0;
            DB::transaction(function () use ($id, &$message) {
                $religion = Religion::with('employees')->findOrFail($id);
                // dd($religion);
                if ($religion->employees->count() === 0) {
                    $religion->delete();          
                    $message = ['message'=>'Religion deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('religions.index')->with($message);
        } catch(\Exception $e){
            return redirect()->back()->with('error','Something went wrong');
        }
    }
}
