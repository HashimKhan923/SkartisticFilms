<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Movie;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /* ──────────────────────────────────────────
     *  AUTH
     * ────────────────────────────────────────── */

    public function loginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.panel');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.panel');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /* ──────────────────────────────────────────
     *  PANEL
     * ────────────────────────────────────────── */

    public function panel()
    {
        $settings   = SiteSetting::pluck('value', 'key');
        $movies     = Movie::with('characters')->orderBy('sort_order')->get();
        $characters = Character::orderBy('sort_order')->get();
        $banners    = \App\Models\Banner::orderBy('sort_order')->get();
        return view('admin.panel', compact('settings', 'movies', 'characters', 'banners'));
    }

    /* ──────────────────────────────────────────
     *  SETTINGS
     * ────────────────────────────────────────── */

public function saveSettings(Request $request)
{
    $request->validate([
        'site_name'       => 'required|string|max:100',
        'site_tagline'    => 'nullable|string|max:200',
        'about_title'     => 'nullable|string|max:200',
        'about_text'      => 'nullable|string',
        'founded_year'    => 'nullable|digits:4',
        'hero_type'       => 'nullable|in:image,video_upload,youtube',
        'hero_title'      => 'nullable|string|max:200',
        'hero_subtitle'   => 'nullable|string|max:300',
        'hero_youtube'    => 'nullable|url',
        'contact_email'   => 'nullable|email',
        'contact_phone'   => 'nullable|string|max:50',
        'contact_address' => 'nullable|string|max:255',
        'facebook'        => 'nullable|url',
        'instagram'       => 'nullable|url',
        'youtube'         => 'nullable|url',
        'footer_text'     => 'nullable|string|max:255',
        'logo'            => 'nullable|image|max:2048',
        'about_image'     => 'nullable|image|max:4096',
        'hero_image'      => 'nullable|image|max:8192',
        'hero_video_file' => 'nullable|mimetypes:video/mp4,video/webm|max:204800',
    ]);

    // ── File uploads ──────────────────────────────
    $fileFields = ['logo', 'about_image', 'hero_image', 'hero_video_file'];

    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            $old = SiteSetting::get($field);
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file($field)->store("settings/{$field}", 'public');
            SiteSetting::set($field, $path);
        }
    }

    // ── Text fields — only save if present in request ──
    $textFields = [
        'site_name', 'site_tagline', 'about_title', 'about_text', 'founded_year',
        'hero_title', 'hero_subtitle', 'hero_youtube',
        'contact_email', 'contact_phone', 'contact_address',
        'facebook', 'instagram', 'youtube', 'footer_text',
    ];

    foreach ($textFields as $field) {
        if ($request->has($field)) {
            SiteSetting::set($field, $request->input($field, ''));
        }
    }

    // ── hero_type — only update if submitted ──────
    if ($request->filled('hero_type')) {
        SiteSetting::set('hero_type', $request->input('hero_type'));
    }

    return back()->with('success', 'Settings saved successfully.');
}

    /* ──────────────────────────────────────────
     *  MOVIES
     * ────────────────────────────────────────── */

    public function storeMovie(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:200',
            'genre'         => 'nullable|string|max:100',
            'year'          => 'nullable|digits:4',
            'description'   => 'nullable|string',
            'rating'        => 'nullable|numeric|min:0|max:10',
            'duration'      => 'nullable|integer|min:1',
            'is_featured'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
            'video_type'    => 'required|in:youtube,upload',
            'video_youtube' => 'nullable|url',
            'poster'        => 'nullable|image|max:4096',
            'banner'        => 'nullable|image|max:8192',
            'video_file'    => 'nullable|mimetypes:video/mp4,video/webm|max:204800',
        ]);

        $movie = new Movie();
        $movie->fill([
            'title'       => $data['title'],
            'genre'       => $data['genre'] ?? null,
            'year'        => $data['year'] ?? null,
            'description' => $data['description'] ?? null,
            'rating'      => $data['rating'] ?? null,
            'duration'    => $data['duration'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order'  => $data['sort_order'] ?? 0,
            'video_type'  => $data['video_type'],
            'video_youtube' => $data['video_youtube'] ?? null,
        ]);

        foreach (['poster', 'banner'] as $img) {
            if ($request->hasFile($img)) {
                $movie->$img = $request->file($img)->store("movies/{$img}", 'public');
            }
        }

        if ($request->hasFile('video_file')) {
            $movie->video_file = $request->file('video_file')->store('movies/videos', 'public');
        }

        $movie->save();
        return back()->with('success', "Movie \"{$movie->title}\" added.");
    }

    public function updateMovie(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:200',
            'genre'         => 'nullable|string|max:100',
            'year'          => 'nullable|digits:4',
            'description'   => 'nullable|string',
            'rating'        => 'nullable|numeric|min:0|max:10',
            'duration'      => 'nullable|integer|min:1',
            'is_featured'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
            'video_type'    => 'required|in:youtube,upload',
            'video_youtube' => 'nullable|url',
            'poster'        => 'nullable|image|max:4096',
            'banner'        => 'nullable|image|max:8192',
            'video_file'    => 'nullable|mimetypes:video/mp4,video/webm|max:204800',
        ]);

        $movie->fill([
            'title'         => $data['title'],
            'genre'         => $data['genre'] ?? null,
            'year'          => $data['year'] ?? null,
            'description'   => $data['description'] ?? null,
            'rating'        => $data['rating'] ?? null,
            'duration'      => $data['duration'] ?? null,
            'is_featured'   => $request->boolean('is_featured'),
            'sort_order'    => $data['sort_order'] ?? 0,
            'video_type'    => $data['video_type'],
            'video_youtube' => $data['video_youtube'] ?? null,
        ]);

        foreach (['poster', 'banner'] as $img) {
            if ($request->hasFile($img)) {
                if ($movie->$img) Storage::disk('public')->delete($movie->$img);
                $movie->$img = $request->file($img)->store("movies/{$img}", 'public');
            }
        }

        if ($request->hasFile('video_file')) {
            if ($movie->video_file) Storage::disk('public')->delete($movie->video_file);
            $movie->video_file = $request->file('video_file')->store('movies/videos', 'public');
        }

        $movie->save();
        return back()->with('success', "Movie \"{$movie->title}\" updated.");
    }

    public function deleteMovie(Movie $movie)
    {
        foreach (['poster', 'banner', 'video_file'] as $f) {
            if ($movie->$f) Storage::disk('public')->delete($movie->$f);
        }
        $movie->delete();
        return back()->with('success', 'Movie deleted.');
    }

    /* ──────────────────────────────────────────
     *  CHARACTERS
     * ────────────────────────────────────────── */

    public function storeCharacter(Request $request)
    {
        $data = $request->validate([
            'movie_id'   => 'nullable|exists:movies,id',
            'name'       => 'required|string|max:150',
            'actor_name' => 'nullable|string|max:150',
            'role'       => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'photo'      => 'nullable|image|max:4096',
        ]);

        $character = new Character();
        $character->fill([
            'movie_id'   => $data['movie_id'] ?? null,
            'name'       => $data['name'],
            'actor_name' => $data['actor_name'] ?? null,
            'role'       => $data['role'] ?? null,
            'bio'        => $data['bio'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('photo')) {
            $character->photo = $request->file('photo')->store('characters', 'public');
        }

        $character->save();
        return back()->with('success', "Character \"{$character->name}\" added.");
    }

    public function updateCharacter(Request $request, Character $character)
    {
        $data = $request->validate([
            'movie_id'   => 'nullable|exists:movies,id',
            'name'       => 'required|string|max:150',
            'actor_name' => 'nullable|string|max:150',
            'role'       => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'photo'      => 'nullable|image|max:4096',
        ]);

        $character->fill([
            'movie_id'   => $data['movie_id'] ?? null,
            'name'       => $data['name'],
            'actor_name' => $data['actor_name'] ?? null,
            'role'       => $data['role'] ?? null,
            'bio'        => $data['bio'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($request->hasFile('photo')) {
            if ($character->photo) Storage::disk('public')->delete($character->photo);
            $character->photo = $request->file('photo')->store('characters', 'public');
        }

        $character->save();
        return back()->with('success', "Character \"{$character->name}\" updated.");
    }

    public function deleteCharacter(Character $character)
    {
        if ($character->photo) Storage::disk('public')->delete($character->photo);
        $character->delete();
        return back()->with('success', 'Character deleted.');
    }

    /* ──────────────────────────────────────────
     *  COUNTDOWN TIMER SETTINGS
     * ────────────────────────────────────────── */

    public function saveTimerSettings(Request $request)
    {
        $request->validate([
            'timer_title'      => 'nullable|string|max:200',
            'timer_subtitle'   => 'nullable|string|max:300',
            'timer_date'       => 'nullable|date',
            'timer_image'      => 'nullable|image|max:8192',
            'timer_active'     => 'nullable|boolean',
        ]);

        $textFields = ['timer_title', 'timer_subtitle', 'timer_date'];
        foreach ($textFields as $f) {
            SiteSetting::set($f, $request->input($f, ''));
        }
        SiteSetting::set('timer_active', $request->boolean('timer_active') ? '1' : '0');

        if ($request->hasFile('timer_image')) {
            $old = SiteSetting::get('timer_image');
            if ($old) Storage::disk('public')->delete($old);
            SiteSetting::set('timer_image', $request->file('timer_image')->store('settings/timer', 'public'));
        }

        return back()->with('success', 'Countdown timer settings saved.');
    }

    /* ──────────────────────────────────────────
     *  BANNERS
     * ────────────────────────────────────────── */

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'genre'      => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable|image|max:8192',
        ]);

        $banner = new \App\Models\Banner();
        $banner->title      = $request->title;
        $banner->genre      = $request->genre;
        $banner->sort_order = $request->input('sort_order', 0);
        $banner->is_active  = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return back()->with('success', "Banner \"{$banner->title}\" added.");
    }

    public function updateBanner(Request $request, \App\Models\Banner $banner)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'genre'      => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable|image|max:8192',
        ]);

        $banner->title      = $request->title;
        $banner->genre      = $request->genre;
        $banner->sort_order = $request->input('sort_order', 0);
        $banner->is_active  = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return back()->with('success', "Banner \"{$banner->title}\" updated.");
    }

    public function deleteBanner(\App\Models\Banner $banner)
    {
        if ($banner->image) Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }
}