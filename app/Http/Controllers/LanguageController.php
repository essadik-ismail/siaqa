<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Validate the locale
        $allowedLocales = ['en', 'fr', 'ar'];
        
        if (!in_array($locale, $allowedLocales)) {
            $locale = 'fr'; // Default to French
        }
        
        // Store the locale in session
        Session::put('locale', $locale);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}
