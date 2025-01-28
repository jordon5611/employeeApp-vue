<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET|HEAD    employee ........ employee.index › EmployeeController@index
    //  */

    public function index()
    {
        $sortColumn = request()->get('sort', 'id'); // Default sort by 'id'
        $sortDirection = request()->get('direction', 'asc'); // Default sort direction is ascending
        $search = request()->get('search', ''); // Search query (default empty)

        // Start building the query
        $query = Employee::query();


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
        if ($sortColumn === 'country') {
            $query->join('countries', 'employees.country_id', '=', 'countries.id')
                ->select('employees.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } elseif ($sortColumn === 'state') {
            $query->join('states', 'employees.state_id', '=', 'states.id')
                ->select('employees.*', 'states.name as state_name')
                ->orderBy('states.name', $sortDirection);
        } elseif ($sortColumn === 'city') {
            $query->join('cities', 'employees.city_id', '=', 'cities.id')
                ->select('employees.*', 'cities.name as city_name')
                ->orderBy('cities.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Paginate results
        $employees = $query->paginate(10)->withQueryString();

        return view("employee.index", compact('employees', 'sortColumn', 'sortDirection'));
    }


    /**
     * Show the form for creating a new resource.
     * GET|HEAD    employee/create ......... employee.create › EmployeeController@create
     * 
     */
    public function create()
    {
        $countries = Country::orderBy('name', 'asc')->get();

        return view("employee.create", compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * POST          employee ........ employee.store › EmployeeController@store
     */
    public function store(EmployeeRequest $request)
    {
        //dd(request()->all());

        $attributes = $request->validated();

        // Hash the password before saving
        $attributes['password'] = Hash::make($attributes['password']);

        Employee::create($attributes);

        return redirect()->route('employee.index')->with('success', __('errorMessages.employee_create_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {

        // Pass the employee details to the view
        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $countries = Country::orderBy('name', 'asc')->get();
        // Get states for the employee's country
        $states = State::where('country_id', $employee->country_id)
            ->orderBy('name', 'asc')
            ->get();

        // Get cities for the employee's state
        $cities = City::where('state_id', $employee->state_id)
            ->orderBy('name', 'asc')
            ->get();

        return view("employee.create", ['employee' => $employee, 'countries' => $countries, 'cities' => $cities, 'states' => $states]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        // dd(request()->all());

        $attributes = $request->validated();


        // Only hash and update the password if it was provided
        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            // Remove password from attributes to avoid updating it
            unset($attributes['password']);
        }

        // Update the employee record
        $employee->update($attributes);


        return redirect()->route('employee.index')->with("success");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {

        employee::destroy($employee->id);

        return redirect()->route('employee.index')->with('success', __('errorMessages.employee_delete_success'));
    }
}
