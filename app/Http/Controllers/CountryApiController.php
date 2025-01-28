<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Models\Country;

class CountryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortColumn = request()->get('sort', 'id'); // Default sort by 'id'
        $sortDirection = request()->get('direction', 'asc'); // Default sort direction is ascending
        $search = request()->get('search', ''); // Search query (default empty)

        // Start building the query
        $query = Country::query();

        // Add search functionality
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Apply sorting
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results
        $countries = $query->paginate(10)->withQueryString();

        //return view("country.index", compact('countries', 'sortColumn', 'sortDirection'));

        return response()->json($countries, 200);
    
    }

    public function all()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        return response()->json($countries, 200);   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        $countryAttributes = $request->validated();

        Country::create($countryAttributes);

        return response()->json([
            'status' => 'success',
            'statusCode' => 201,
            'message' => __('errorMessages.country_create_success'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Country::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, string $id)
    {
        $existingCountry = Country::findOrFail($id);

        // request()->validate([
        //     "name" => ["required", "string", "max:255"],
        // ]);

        $request->validated();

        $existingCountry->name = request()->name;
        $existingCountry->save();


        // return redirect()->route('country.index')->with("success");
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => __('errorMessages.country_update_success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::find($id);

        // Check if the country has associated states or employees
        if ($country->states()->exists() || $country->employees()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('errorMessages.country_delete_failed'),
            ], 400);
        }

        $country->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.country_delete_success'),
        ]);
    }
}
