<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployerDetailsController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('country.index', ['countries' => 'Pakistan, India, Bangladesh']);
// });

// Route::resource('country', CountryController::class);


// Route::resource('state', StateController::class);


// Route::resource('city', CityController::class);

// Route::resource('employee', EmployeeController::class);

// Route::get('/locale/{lang}', [EmployerDetailsController::class, 'changeLang'])->name('locale.change');

Route::get('/states/{country}', [EmployerDetailsController::class, 'getStates']);
Route::get('/cities/{state}', [EmployerDetailsController::class, 'getCities']);



Route::get('/{any}', function () {
    return view('app'); // The main Vue entry point
})->where('any', '.*');


