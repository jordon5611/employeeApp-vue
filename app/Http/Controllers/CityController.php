<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
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

        return view('city.index', compact('cities', 'sortColumn', 'sortDirection'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("city.create", [
            'states' => State::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *   POST            city .... city.store › CityController@store
     */
    public function store(CityRequest $request)
    {

        //dd(request()->all());
        $validated = $request->validated();

        City::create($validated);

        return redirect()->route('city.index')->with('success', __('errorMessages.city_create_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $city)
    {
        return view("city.create", [
            "city" => city::find($city),
            "states" => State::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, string $city)
    {

        $existingCity = City::findOrFail($city);

        $request->validated();

        $existingCity->name = request()->name;
        $existingCity->state_id = request()->state_id;

        $existingCity->save();


        return redirect()->route('city.index')->with('success', __('errorMessages.city_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $city)
    {
        $ExistingCity = City::findOrFail($city);

        // Check if the city has associated employees
        if ($ExistingCity->employees()->exists() ) {
            return redirect()
                ->route('city.index')
                ->with('error', __('errorMessages.city_delete_failed'));
        }

        $ExistingCity->delete();

        return redirect()->route('city.index')->with('success', __('errorMessages.city_delete_success'));
    }
}
