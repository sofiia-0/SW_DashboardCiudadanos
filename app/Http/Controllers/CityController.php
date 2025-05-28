<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CitiesImport;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cities = City::paginate(6);
            return view('cities.index', compact('cities'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('cities.create');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load create city form'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            City::create($request->all());
            return redirect()->route('cities.index')->with('success', 'City created successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create city'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $city = City::findOrFail($id);
            return view('cities.show', compact('city'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch city'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $city = City::findOrFail($id);
            return view('cities.edit', compact('city'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load edit city form'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $city = City::findOrFail($id);
            $city->update($request->all());
            return redirect()->route('cities.index')->with('success', 'City updated successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update city'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
    try {
        $city = City::findOrFail($id);

        // Verifica si hay ciudadanos registrados en esta ciudad
        if ($city->citizens()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la ciudad porque tiene ciudadanos registrados.');
        }

        $city->delete();
        return redirect()->route('cities.index')->with('success', 'City deleted successfully');
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete city'], 500);
    }
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv',
    ]);

    Excel::import(new CitiesImport, $request->file('file'));

    return redirect()->route('cities.index')->with('success', 'Ciudades importadas correctamente.');
}

}
