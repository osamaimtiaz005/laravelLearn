<?php

namespace App\Http\Controllers;

class LocalizationController extends Controller
{
    public function welcome(string $locale)
    {
        // Locale is set by SetLocale middleware from session / route param
        return view('localization.welcome', [
            'locale' => session('locale', $locale),
        ]);
    }
}
