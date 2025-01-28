<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class LocalizationApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log session details for debugging
        \Log::info('Session ID: ' . session()->getId());
        \Log::info('Session Data: ' . json_encode(session()->all()));

        // Retrieve locale from the session or use default
        $locale = session('locale'); // Default to 'en' if no session value exists

        //\Log::info('Locale Retrieved from Session: ' . $locale);

        if ($locale) {
            \Log::info('locale: ' . $locale);
            //Set the application locale
            App::setLocale($locale);

            // Save the locale back to the session
            session(['locale' => $locale]);
        }
        \Log::info('App::getLocale(): ' . App::getLocale());

        return $next($request);
    }
}
