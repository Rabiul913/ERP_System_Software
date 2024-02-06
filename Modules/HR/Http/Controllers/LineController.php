<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Line;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $lines = Line::latest()->get();
        return view('hr::line.index', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        return view('hr::line.create', compact('formType'));
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
                Line::create($input);
            });

            return redirect()->route('lines.index')->with('message', 'Line created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('lines.edit')->withInput()->withErrors($e->getMessage());
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
        $line = Line::findOrFail($id);
        return view('hr::line.create', compact('formType', 'line'));
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
                Line::findOrFail($id)->update($input);
            });

            return redirect()->route('lines.index')->with('message', 'Line updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('lines.edit')->withInput()->withErrors($e->getMessage());
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
                $line = Line::with('employees')->findOrFail($id);
                // dd($line);
                if ($line->employees->count() === 0) {
                    $line->delete();          
                    $message = ['message'=>'Line deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('lines.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('lines.index')->withErrors($e->getMessage());
        }
    }
}
