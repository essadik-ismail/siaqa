<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicule;
use App\Models\Tenant;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }
    
    public function showRegister()
    {
        return view('landing.register');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect directly to dashboard without success message
            return redirect()->route('dashboard');
        }
        
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
    
    
    
    /**
     * Get the current tenant based on domain or default to first active tenant
     */
    private function getCurrentTenant()
    {
        $host = request()->getHost();
        
        // Try to find tenant by website (domain)
        $tenant = Tenant::where('website', $host)->first();
        
        if ($tenant && $tenant->is_active) {
            return $tenant;
        }
        
        // Fallback to first active tenant (for development/testing)
        return Tenant::where('is_active', true)->first();
    }
}
