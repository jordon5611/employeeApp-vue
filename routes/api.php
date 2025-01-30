<?php

use App\Http\Controllers\CityApiController;
use App\Http\Controllers\CountryApiController;
use App\Http\Controllers\EmployeeApiController;
use App\Http\Controllers\EmployerDetailsController;
use App\Http\Controllers\StateApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/countries/all', [CountryApiController::class, 'all']);
Route::apiResource('country', CountryApiController::class);

Route::get('/states/all', [StateApiController::class, 'all']);
Route::apiResource('state', StateApiController::class);

Route::apiResource('city', CityApiController::class);

Route::apiResource('employee', EmployeeApiController::class);

// routes/api.php
Route::get('/locale/{lang}', [EmployerDetailsController::class, 'changeLang'])->name('locale.change');


Route::get('/translations/{locale}', function ($locale) { //{type} in func($type)
    if (!in_array($locale, ['en', 'ur'])) {
        abort(404);
    }

    App::setLocale($locale);

    // Fetch translations for all required namespaces
    $translations = [
        // $type => Lang::get($type), //type
        'country' => Lang::get('country'),
        'state' => Lang::get('state'),
        'city' => Lang::get('city'),
        'components' => Lang::get('components'),
        'popups' => Lang::get('popups'),
        'employee' => Lang::get('employee'),
        'layout' => Lang::get('layout'),
        'errorMessages' => Lang::get('errorMessages'),
    ];

    return response()->json([
        'translations' => $translations,
    ]);
});

Route::get('/locale', [EmployerDetailsController::class, 'currentLocale']);

Route::post('/country/{id}/restore', [CountryApiController::class, 'restore']); // Restore
Route::delete('/country/{id}/force-delete', [CountryApiController::class, 'forceDelete']); // Permanently delete


Route::post('/state/{id}/restore', [StateApiController::class, 'restore']); // Restore
Route::delete('/state/{id}/force-delete', [StateApiController::class, 'forceDelete']); // Permanently delete


Route::post('/city/{id}/restore', [CityApiController::class, 'restore']); // Restore
Route::delete('/city/{id}/force-delete', [CityApiController::class, 'forceDelete']); // Permanently delete


Route::post('/employee/{id}/restore', [EmployeeApiController::class, 'restore']); // Restore
Route::delete('/employee/{id}/force-delete', [EmployeeApiController::class, 'forceDelete']); // Permanently delete