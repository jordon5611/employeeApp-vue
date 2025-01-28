<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeApiController extends Controller
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
        $query = Employee::with(['country', 'state', 'city']);

        // Add search functionality
        if (!empty($search)) {
            $query->where('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%') // Adjust fields as needed
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhereHas('country', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        // Sorting logic
        if ($sortColumn === 'country_id') {
            $query->join('countries', 'employees.country_id', '=', 'countries.id')
                ->select('employees.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } elseif ($sortColumn === 'state_id') {
            $query->join('states', 'employees.state_id', '=', 'states.id')
                ->select('employees.*', 'states.name as state_name')
                ->orderBy('states.name', $sortDirection);
        } elseif ($sortColumn === 'city_id') {
            $query->join('cities', 'employees.city_id', '=', 'cities.id')
                ->select('employees.*', 'cities.name as city_name')
                ->orderBy('cities.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Paginate results
        $employees = $query->paginate(10)->withQueryString();

        return response()->json($employees, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $attributes = $request->validated();

        // Hash the password before saving
        $attributes['password'] = Hash::make($attributes['password']);

        Employee::create($attributes);

        return response()->json([
            'status' => 'success',
            'statusCode' => 201,
            'message' => __('errorMessages.employee_create_success'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with('state', 'city', 'country')->findOrFail($id);

        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id)
    {
        //dd('adasd');
        $attributes = $request->validated();

        $employee = Employee::findOrFail($id);


        // Only hash and update the password if it was provided
        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            // Remove password from attributes to avoid updating it
            unset($attributes['password']);
        }

        // Update the employee record
        $employee->update($attributes);

        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => __('errorMessages.employee_update_success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        employee::destroy($id);

        return response()->json([
            'status' => 'success',
            'message' => __('errorMessages.employee_delete_success'),
        ]);
    }
}
