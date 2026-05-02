<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Character;
use App\Models\Movie;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $settings   = SiteSetting::pluck('value', 'key');
        $movies     = Movie::orderBy('sort_order')->orderByDesc('year')->get();
        $featured   = Movie::where('is_featured', true)->first() ?? $movies->first();
        $characters = Character::orderBy('sort_order')->get();
        $banners    = Banner::where('is_active', true)
                            ->orderBy('sort_order')
                            ->take(6)
                            ->get();

        return view('home', compact('settings', 'movies', 'featured', 'characters', 'banners'));
    }
}