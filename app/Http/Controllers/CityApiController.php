<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Requests\CityRequest;
use App\Models\State;
use Illuminate\Support\Facades\App;

class CityApiController extends Controller
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
        $query = City::query()->with(['state', 'state.country']);

        if (request()->has('archived') && request()->archived == "true") {
            $query->onlyTrashed(); // Fetch only soft-deleted records
        }

        // Add search functionality
        if (!empty($search)) {
            $query->where('cities.name', 'like', '%' . $search . '%')
                ->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('country', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
        }

        // Sorting logic
        if ($sortColumn === 'state_id') {
            $query->join('states', 'cities.state_id', '=', 'states.id')
                ->select('cities.*', 'states.name as state_name')
                ->orderBy('states.name', $sortDirection);
        } elseif ($sortColumn === 'country_id') {
            $query->join('states', 'cities.state_id', '=', 'states.id')
                ->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('cities.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Paginate with query string to retain search and sort parameters
        $cities = $query->paginate(10)->withQueryString();

        return response()->json($cities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        $validated = $request->validated();

        City::create($validated);

        return response()->json([
            'status' => 'success',
            'statusCode' => 201,
            'message' => __('errorMessages.city_create_success'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $city = City::with('state')->findOrFail($id);

        return response()->json($city);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, string $id)
    {
        $existingCity = City::findOrFail($id);

        $request->validated();

        $existingCity->name = request()->name;
        $existingCity->state_id = request()->state_id;

        $existingCity->save();

        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => __('errorMessages.city_update_success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ExistingCity = City::findOrFail($id);

        // Check if the city has associated employees
        if ($ExistingCity->employees()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('errorMessages.city_delete_failed'),
            ], 400);
        }

        $ExistingCity->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.city_delete_success'),
        ]);
    }

    public function restore($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->restore();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.city_restore_success'),
        ]);
    }

    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.city_permanent_delete_success'),
        ]);
    }
}
