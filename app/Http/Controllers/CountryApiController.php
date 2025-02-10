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
        // $sortColumn = request()->get('sort', 'id'); // Default sort by 'id'
        // $sortDirection = request()->get('direction', 'asc'); // Default sort direction is ascending
        // $search = request()->get('search', ''); // Search query (default empty)

        // // Start building the query
        // $query = Country::query();

        // if (request()->has('archived') && request()->archived == "true") {
        //     $query->onlyTrashed(); // Fetch only soft-deleted records
        // }

        // // Add search functionality
        // if (!empty($search)) {
        //     $query->where('name', 'like', '%' . $search . '%');
        // }

        // // Apply sorting
        // $query->orderBy($sortColumn, $sortDirection);

        // // Paginate results
        // $countries = $query->paginate(10)->withQueryString();

        //return view("country.index", compact('countries', 'sortColumn', 'sortDirection'));

        $countries = Country::query()
            ->search(request()->get('search', ''))
            ->archived(request()->get('archived', 'false'))
            ->sort(request()->get('sort', 'id'), request()->get('direction', 'asc'))
            ->paginate(10)
            ->withQueryString();

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
        $country = Country::findOrFail($id);

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

    public function archived()
    {
        $countries = Country::onlyTrashed()->get();

        return response()->json($countries);
    }

    public function restore($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $country->restore();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.country_restore_success'),
        ]);
    }

    public function forceDelete($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $country->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.country_permanent_delete_success'),
        ]);
    }
}
