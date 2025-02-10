<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeDetails;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class EmployerDetailsController extends Controller
{


    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->orderBy('name', 'asc')->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->orderBy('name', 'asc')->get();
        return response()->json($cities);
    }



    public function changeLang($lang)
    {
        if (in_array($lang, ['en', 'ur'])) {
            session(['locale' => $lang]); // Store locale in session
            session()->save(); // Explicitly save session
            App::setLocale($lang);
        }

        //return redirect()->back();
        return response()->json(['success' => true, 'locale_in_session' => session('locale')]);
    }

    public function currentLocale()
    {
        $locale = session('locale'); // Default to 'en' if not set

        return response()->json(['locale' => $locale]);
    }

    

}
