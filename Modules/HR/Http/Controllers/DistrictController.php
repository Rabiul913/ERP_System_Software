<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HR\Entities\Country;
use Modules\HR\Entities\District;
use Modules\HR\Entities\Division;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $districts = District::latest()->get();
        return view('hr::district.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $formType = 'create';
        $country=Country::orderBy('name')->pluck('name', 'id');
        return view('hr::district.create', compact('formType', 'country'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $input = $request->except(['country_id']);
            DB::transaction(function () use ($input, $request) {
                District::create($input);
            });

            return redirect()->route('districts.index')->with('message', 'District created successfully.');
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
        $country=Country::orderBy('name')->pluck('name', 'id');
        $district = District::findOrFail($id);
        return view('hr::district.create', compact('formType', 'district','country'));
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
            $input = $request->except(['country_id']);
            DB::transaction(function () use ($input, $request, $id) {
                $district = District::findOrFail($id);
                $district->update($input);
            });

            return redirect()->route('districts.index')->with('message', 'District updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('districts.edit')->withInput()->withErrors($e->getMessage());
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
                $district = District::with('policestations')->findOrFail($id);
                if ($district->policestations->count() === 0) {
                    $district->delete();             
                    $message = ['message'=>'District deleted successfully.'];
                } else {
                    $message = ['error'=>'This data has some dependency.'];
                }
            });
            
            return redirect()->route('districts.index')->with($message);
        } catch (QueryException $e) {
            return redirect()->route('districts.index')->withErrors($e->getMessage());
        }
    }

    public function fetchDivisions(Request $request)
    {
        $countryId = $request->input('country_id');

        $divisions = Division::where('country_id', $countryId)->get();

        // Return the HTML for the Division dropdown options
        if (count($divisions)>0) {
            $html = '<option value="">Select a division</option>';
            foreach ($divisions as $division) {
                $html .= '<option value="' . $division->id . '">' . $division->name . '</option>';
            }
        }
        else{
            $html = '<option value="">No Divisions</option>';
        }
        return $html;
    }

    public function fetchDistricts(Request $request)
    {
        $divisionId = $request->input('division_id');
        $key = $request->input('key');
        // dd($key);
        $districts = District::where('division_id', $divisionId)->get();

        // Return the HTML for the Division dropdown options
        if (count($districts)>0) {
            $html = '<option value="">Select a division</option>';
            foreach ($districts as $district) { 
                $html .= '<option value="' . $district->id . '" ' . (($key == $district->id) ? "selected" : "") . '>' . $district->name . '</option>';
            }
        }
        else{
            $html = '<option value="">No Districts</option>';
        }
        return $html;
    }
}
