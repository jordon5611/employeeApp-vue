<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\Country;
use App\Models\State;

class StateApiController extends Controller
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

        if (request()->has('archived') && request()->archived == "true") {
            $query->onlyTrashed(); // Fetch only soft-deleted records
        }
        
        // Add search functionality
        if (!empty($search)) {
            $query->where('states.name', 'like', '%' . $search . '%') // Search by state name
            ->orWhereHas('country', function ($q) use ($search) { // Search by country name
                $q->where('countries.name', 'like', '%' . $search . '%');
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

        return response()->json($states, 200);
    }

    public function all()
    {
        $state = State::orderBy('name', 'asc')->get();
        return response()->json($state, 200);   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StateRequest $request)
    {
        $validated = $request->validated();

        State::create($validated);

        return response()->json([
            'status' => 'success',
            'statusCode' => 201,
            'message' => __('errorMessages.state_create_success'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $state = State::with('country')->findOrFail($id);

        return response()->json($state);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StateRequest $request, string $id)
    {
        $existingState = State::findOrFail($id);

        $request->validated();

        $existingState->name = request()->name;
        $existingState->country_id = request()->country_id;

        $existingState->save();


        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => __('errorMessages.state_update_success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ExistingState = State::findOrFail($id);

        // Check if the country has associated states or employees
        if ($ExistingState->cities()->exists() || $ExistingState->employees()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('errorMessages.state_delete_failed'),
            ], 400);
        }

        $ExistingState->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.state_delete_success'),
        ]);
    }

    public function restore($id)
    {
        $state = State::onlyTrashed()->findOrFail($id);
        $state->restore();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.state_restore_success'),
        ]);
    }

    public function forceDelete($id)
    {
        $state = State::onlyTrashed()->findOrFail($id);
        $state->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.state_permanent_delete_success'),
        ]);
    }
}
