<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Agence;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Home page
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/') . '</loc>';
        $sitemap .= '<lastmod>' . Carbon::now()->toW3cString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';
        
        // Cars listing page
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/cars') . '</loc>';
        $sitemap .= '<lastmod>' . Carbon::now()->toW3cString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>0.9</priority>';
        $sitemap .= '</url>';
        
        // Landing page
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/landing') . '</loc>';
        $sitemap .= '<lastmod>' . Carbon::now()->toW3cString() . '</lastmod>';
        $sitemap .= '<changefreq>weekly</changefreq>';
        $sitemap .= '<priority>0.8</priority>';
        $sitemap .= '</url>';
        
        // Individual car pages
        $cars = Vehicule::where('statut', 'disponible')->get();
        foreach ($cars as $car) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/cars/' . $car->id) . '</loc>';
            $sitemap .= '<lastmod>' . $car->updated_at->toW3cString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        // Agency pages
        $agencies = Agence::where('is_active', true)->get();
        foreach ($agencies as $agency) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/agencies/' . $agency->id) . '</loc>';
            $sitemap .= '<lastmod>' . $agency->updated_at->toW3cString() . '</lastmod>';
            $sitemap .= '<changefreq>monthly</changefreq>';
            $sitemap .= '<priority>0.6</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}


