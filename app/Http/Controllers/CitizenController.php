<?php

namespace App\Http\Controllers;

use App\Imports\CitizensImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Citizen;
use App\Models\City;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request) {
    try {
        $busqueda = $request->input('busqueda');

        // En controlador:
     $citizens = Citizen::with('city')
    ->when($busqueda, function ($query, $busqueda) {
        return $query->where('first_name', 'like', '%' . $busqueda . '%')
            ->orWhere('last_name', 'like', '%' . $busqueda . '%')
            ->orWhereHas('city', function ($q) use ($busqueda) {
                $q->where('name', 'like', '%' . $busqueda . '%');
            });
    })
    ->orderBy('first_name', 'asc')
    ->paginate(6);
    return view('citizens.index', compact('citizens', 'busqueda'));

    } catch (\Exception $e) {
        return redirect()->route('citizens.index')->with('error', 'Error fetching citizens: ' . $e->getMessage());
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $cities = City::orderBy('name', 'asc')->get();
            return view('citizens.create', compact('cities'));
        }catch(\Exception $e){
            return redirect()->route('citizens.index')->with('error', 'Error fetching cities: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'birth_date' => 'nullable|date',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
        ]);
        try {
            Citizen::create($request->all());
            return redirect()->route('citizens.index')->with('success', 'Citizen created successfully');
        } catch (\Exception $e) {
            return redirect()->route('citizens.index')->with('error', 'Error creating citizen: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $citizen = Citizen::findOrFail($id);
            return view('citizens.show', compact('citizen'));
        }catch(\Exception $e){
            return redirect()->route('citizens.index')->with('error', 'Error fetching citizen: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $citizen = Citizen::findOrFail($id);
            $cities = City::orderBy('name', 'asc')->get();
            return view('citizens.edit', compact('citizen', 'cities'));
        }catch(\Exception $e){
            return redirect()->route('citizens.index')->with('error', 'Error fetching citizen: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'birth_date' => 'nullable|date',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
        ]);
        try{
            $citizen = Citizen::findOrFail($id);
            $citizen->update($request->all());
            return redirect()->route('citizens.index')->with('success', 'Citizen updated successfully');
        }catch(\Exception $e){
            return redirect()->route('citizens.index')->with('error', 'Error updating citizen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $citizen = Citizen::findOrFail($id);
            $citizen->delete();
            return redirect()->route('citizens.index')->with('success', 'Citizen deleted successfully');
        }catch(\Exception $e){
            return redirect()->route('citizens.index')->with('error', 'Error deleting citizen: ' . $e->getMessage());
        }
    }

    /*
     * Import citizens from an Excel file.
     */
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv,xls',
    ]);

    Excel::import(new CitizensImport, $request->file('file'));

    return redirect()->back()->with('success', 'Ciudadanos importados correctamente');
}
}