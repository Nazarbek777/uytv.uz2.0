<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch application locale
     */
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, config('app.available_locales', ['uz', 'ru', 'en']))) {
            $locale = config('app.locale', 'uz');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return redirect()->back();
    }
}
