<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortColumn = request()->get('sort', 'id'); // Default sort by 'id'
        $sortDirection = request()->get('direction', 'asc'); // Default sort direction is ascending
        $search = request()->get('search'); // Capture the search input

        // Start building the query
        $query = State::query()->with('country'); // Include related country data

        // Add search functionality
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%') // Search by state name
                ->orWhereHas('country', function ($q) use ($search) { // Search by country name
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        // Sorting logic
        if ($sortColumn === 'country_id') {
            $query->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('states.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Paginate the results with query string
        $states = $query->paginate(10)->withQueryString();

        return view("state.index", compact("states", 'sortColumn', 'sortDirection', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("state.create", [
            'countries' => Country::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StateRequest $request)
    {
        $validated = $request->validated();

        State::create($validated);

        return redirect()->route('state.index')->with('success', __('errorMessages.state_create_success'));
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
    public function edit(string $id)
    {
        return view("state.create", [
            "state" => State::find($id),
            "countries" => Country::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StateRequest $request, string $state)
    {

        $existingState = State::findOrFail($state);

        $request->validated();

        $existingState->name = request()->name;
        $existingState->country_id = request()->country_id;

        $existingState->save();


        return redirect()->route('state.index')->with('success', __('errorMessages.state_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $state)
    {
        $ExistingState = State::findOrFail($state);


        // Check if the country has associated states or employees
        if ($ExistingState->cities()->exists() || $ExistingState->employees()->exists()) {
            return redirect()
                ->route('state.index')
                ->with('error', __('errorMessages.state_delete_failed'));
        }

        $ExistingState->delete();

        return redirect()->route('state.index')->with('success', __('errorMessages.state_delete_success'));
    }
}
