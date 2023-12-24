<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
     /**
     * Lanaguage Change
     *
     * @author yarzartinshwe
     *
     * @created 2023-7-5
     *
     */
    public function switch(Request $request, $locale)
    {
        // Store the selected language in the session
        $request->session()->put('locale', $locale);
        App::setLocale(session('locale'));

        // Redirect back to the previous page
        return redirect()->back();
    }
}
