<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET|HEAD        country ........ country.index › CountryController@index
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

        //App::setLocale('ur');


        return view("country.index", compact("countries", 'sortColumn', 'sortDirection', 'search'));
    }

    public function all()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        return response()->json($countries, 200);   
    }


    /**
     * Show the form for creating a new resource.
     *   GET|HEAD        country/create ..... country.create › CountryController@create
     */
    public function create()
    {
        return view("country.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {

        $countryAttributes = $request->validated();

        Country::create($countryAttributes);

        return redirect()->route('country.index')->with('success', __('errorMessages.country_create_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //return response()->json(Country::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     * GET|HEAD        country/{country}/edit ............ country.edit › CountryController@edit
     */
    public function edit(string $country)
    {
        //dd(request()->all());
        return view("country.create", ["country" => Country::find($country)]);
    }

    /**
     * Update the specified resource in storage.
     * PUT|PATCH       country/{country} ......country.update › CountryController@update
     */
    public function update(CountryRequest $request, string $country)
    {
        $existingCountry = Country::findOrFail($country);

        // request()->validate([
        //     "name" => ["required", "string", "max:255"],
        // ]);

        $request->validated();

        $existingCountry->name = request()->name;
        $existingCountry->save();


        return redirect()->route('country.index')->with("success", __('errorMessages.country_update_success'));
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $country)
    {
        $country = Country::find($country);

        // Check if the country has associated states or employees
        if ($country->states()->exists() || $country->employees()->exists()) {
            return redirect()
                ->route('country.index')
                ->with('error', __('errorMessages.country_delete_failed'));
          
        }

        $country->delete();

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Country deleted successfully!',
        // ]);
        return redirect()->route('country.index')->with('success', __('errorMessages.country_delete_success'));
    }
}
