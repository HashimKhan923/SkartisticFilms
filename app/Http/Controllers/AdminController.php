<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Movie;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /* ══════════════════════════════════════
       AUTH
    ══════════════════════════════════════ */

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

    /* ══════════════════════════════════════
       PANEL
    ══════════════════════════════════════ */

    public function panel()
    {
        $settings   = SiteSetting::pluck('value', 'key');
        $movies     = Movie::with('characters')->orderBy('sort_order')->get();
        $characters = Character::orderBy('sort_order')->get();
        $banners    = \App\Models\Banner::orderBy('sort_order')->get();
        $reviews    = Review::with('movie')->orderBy('sort_order')->get();
        $software   = Software::orderBy('sort_order')->get();
        return view('admin.panel', compact('settings', 'movies', 'characters', 'banners', 'reviews', 'software'));
    }

    /* ══════════════════════════════════════
       HELPER — upload file to public/uploads
    ══════════════════════════════════════ */

private function uploadFile($file, string $folder): string
{
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $destination = public_path('uploads/' . $folder);

    // Create folder if it doesn't exist
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    return $folder . '/' . $filename;
}
private function deleteFile(?string $path): void
{
    if ($path && file_exists(public_path('uploads/' . $path))) {
        unlink(public_path('uploads/' . $path));
    }
}

    /* ══════════════════════════════════════
       SETTINGS
    ══════════════════════════════════════ */

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

        // File uploads
        $fileMap = [
            'logo'            => 'settings/logo',
            'about_image'     => 'settings/about_image',
            'hero_image'      => 'settings/hero_image',
            'hero_video_file' => 'settings/hero_video_file',
        ];

        foreach ($fileMap as $field => $folder) {
            if ($request->hasFile($field)) {
                $this->deleteFile(SiteSetting::get($field));
                SiteSetting::set($field, $this->uploadFile($request->file($field), $folder));
            }
        }

        // Text fields
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

        if ($request->filled('hero_type')) {
            SiteSetting::set('hero_type', $request->input('hero_type'));
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    /* ══════════════════════════════════════
       TIMER
    ══════════════════════════════════════ */

    public function saveTimerSettings(Request $request)
    {
        $request->validate([
            'timer_title'    => 'nullable|string|max:200',
            'timer_subtitle' => 'nullable|string|max:300',
            'timer_date'     => 'nullable|date',
            'timer_image'    => 'nullable|image|max:8192',
            'timer_active'   => 'nullable|boolean',
        ]);

        foreach (['timer_title', 'timer_subtitle', 'timer_date'] as $f) {
            SiteSetting::set($f, $request->input($f, ''));
        }

        SiteSetting::set('timer_active', $request->boolean('timer_active') ? '1' : '0');

        if ($request->hasFile('timer_image')) {
            $this->deleteFile(SiteSetting::get('timer_image'));
            SiteSetting::set('timer_image', $this->uploadFile($request->file('timer_image'), 'settings/timer'));
        }

        return back()->with('success', 'Timer settings saved.');
    }

    /* ══════════════════════════════════════
       MOVIES
    ══════════════════════════════════════ */

    public function storeMovie(Request $request)
    {
        $request->validate([
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
            'title'         => $request->title,
            'genre'         => $request->genre,
            'year'          => $request->year,
            'description'   => $request->description,
            'rating'        => $request->rating,
            'duration'      => $request->duration,
            'is_featured'   => $request->boolean('is_featured'),
            'sort_order'    => $request->input('sort_order', 0),
            'video_type'    => $request->video_type,
            'video_youtube' => $request->video_youtube,
        ]);

        if ($request->hasFile('poster')) {
            $movie->poster = $this->uploadFile($request->file('poster'), 'movies/poster');
        }

        if ($request->hasFile('banner')) {
            $movie->banner = $this->uploadFile($request->file('banner'), 'movies/banner');
        }

        if ($request->hasFile('video_file')) {
            $movie->video_file = $this->uploadFile($request->file('video_file'), 'movies/videos');
        }

        $movie->save();
        return back()->with('success', "Movie \"{$movie->title}\" added.");
    }

    public function updateMovie(Request $request, Movie $movie)
    {
        $request->validate([
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
            'title'         => $request->title,
            'genre'         => $request->genre,
            'year'          => $request->year,
            'description'   => $request->description,
            'rating'        => $request->rating,
            'duration'      => $request->duration,
            'is_featured'   => $request->boolean('is_featured'),
            'sort_order'    => $request->input('sort_order', 0),
            'video_type'    => $request->video_type,
            'video_youtube' => $request->video_youtube,
        ]);

        if ($request->hasFile('poster')) {
            $this->deleteFile($movie->poster);
            $movie->poster = $this->uploadFile($request->file('poster'), 'movies/poster');
        }

        if ($request->hasFile('banner')) {
            $this->deleteFile($movie->banner);
            $movie->banner = $this->uploadFile($request->file('banner'), 'movies/banner');
        }

        if ($request->hasFile('video_file')) {
            $this->deleteFile($movie->video_file);
            $movie->video_file = $this->uploadFile($request->file('video_file'), 'movies/videos');
        }

        $movie->save();
        return back()->with('success', "Movie \"{$movie->title}\" updated.");
    }

    public function deleteMovie(Movie $movie)
    {
        $this->deleteFile($movie->poster);
        $this->deleteFile($movie->banner);
        $this->deleteFile($movie->video_file);
        $movie->delete();
        return back()->with('success', 'Movie deleted.');
    }

    /* ══════════════════════════════════════
       CHARACTERS
    ══════════════════════════════════════ */

    public function storeCharacter(Request $request)
    {
        $request->validate([
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
            'movie_id'   => $request->movie_id,
            'name'       => $request->name,
            'actor_name' => $request->actor_name,
            'role'       => $request->role,
            'bio'        => $request->bio,
            'sort_order' => $request->input('sort_order', 0),
        ]);

        if ($request->hasFile('photo')) {
            $character->photo = $this->uploadFile($request->file('photo'), 'characters');
        }

        $character->save();
        return back()->with('success', "Character \"{$character->name}\" added.");
    }

    public function updateCharacter(Request $request, Character $character)
    {
        $request->validate([
            'movie_id'   => 'nullable|exists:movies,id',
            'name'       => 'required|string|max:150',
            'actor_name' => 'nullable|string|max:150',
            'role'       => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'photo'      => 'nullable|image|max:4096',
        ]);

        $character->fill([
            'movie_id'   => $request->movie_id,
            'name'       => $request->name,
            'actor_name' => $request->actor_name,
            'role'       => $request->role,
            'bio'        => $request->bio,
            'sort_order' => $request->input('sort_order', 0),
        ]);

        if ($request->hasFile('photo')) {
            $this->deleteFile($character->photo);
            $character->photo = $this->uploadFile($request->file('photo'), 'characters');
        }

        $character->save();
        return back()->with('success', "Character \"{$character->name}\" updated.");
    }

    public function deleteCharacter(Character $character)
    {
        $this->deleteFile($character->photo);
        $character->delete();
        return back()->with('success', 'Character deleted.');
    }

    /* ══════════════════════════════════════
       BANNERS
    ══════════════════════════════════════ */

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'genre'      => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable|image|max:8192',
        ]);

        $banner             = new \App\Models\Banner();
        $banner->title      = $request->title;
        $banner->genre      = $request->genre;
        $banner->sort_order = $request->input('sort_order', 0);
        $banner->is_active  = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $banner->image = $this->uploadFile($request->file('image'), 'banners');
        }

        $banner->save();
        return back()->with('success', "Banner \"{$banner->title}\" added.");
    }

    public function updateBanner(Request $request, \App\Models\Banner $banner)
    { 
        return 1;
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
            $this->deleteFile($banner->image);
            $banner->image = $this->uploadFile($request->file('image'), 'banners');
        }

        $banner->save();
        return back()->with('success', "Banner \"{$banner->title}\" updated.");
    }

    public function deleteBanner(\App\Models\Banner $banner)
    {
        $this->deleteFile($banner->image);
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }



        public function storePage(Request $request)
    {
        $request->validate([
            'slug'      => 'required|string|max:100|unique:pages,slug|alpha_dash',
            'title'     => 'required|string|max:200',
            'content'   => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        \App\Models\Page::create([
            'slug'      => $request->slug,
            'title'     => $request->title,
            'content'   => $request->content,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Page \"{$request->title}\" created.");
    }

    public function updatePage(Request $request, \App\Models\Page $page)
    {
        $request->validate([
            'slug'      => 'required|string|max:100|alpha_dash|unique:pages,slug,' . $page->id,
            'title'     => 'required|string|max:200',
            'content'   => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $page->update([
            'slug'      => $request->slug,
            'title'     => $request->title,
            'content'   => $request->content,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Page \"{$page->title}\" updated.");
    }

    public function deletePage(\App\Models\Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }

    /* ══════════════════════════════════════
       REVIEWS
    ══════════════════════════════════════ */

    public function storeReview(Request $request)
    {
        $request->validate([
            'reviewer_name'  => 'required|string|max:150',
            'reviewer_title' => 'nullable|string|max:150',
            'body'           => 'required|string',
            'rating'         => 'nullable|integer|min:1|max:5',
            'movie_id'       => 'nullable|exists:movies,id',
            'sort_order'     => 'nullable|integer',
            'is_active'      => 'nullable|boolean',
            'photo'          => 'nullable|image|max:2048',
        ]);

        $review = new Review();
        $review->fill([
            'reviewer_name'  => $request->reviewer_name,
            'reviewer_title' => $request->reviewer_title,
            'body'           => $request->body,
            'rating'         => $request->rating,
            'movie_id'       => $request->movie_id,
            'sort_order'     => $request->input('sort_order', 0),
            'is_active'      => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('photo')) {
            $review->photo = $this->uploadFile($request->file('photo'), 'reviews');
        }

        $review->save();
        return back()->with('success', "Review by \"{$review->reviewer_name}\" added.");
    }

    public function updateReview(Request $request, Review $review)
    {
        $request->validate([
            'reviewer_name'  => 'required|string|max:150',
            'reviewer_title' => 'nullable|string|max:150',
            'body'           => 'required|string',
            'rating'         => 'nullable|integer|min:1|max:5',
            'movie_id'       => 'nullable|exists:movies,id',
            'sort_order'     => 'nullable|integer',
            'is_active'      => 'nullable|boolean',
            'photo'          => 'nullable|image|max:2048',
        ]);

        $review->fill([
            'reviewer_name'  => $request->reviewer_name,
            'reviewer_title' => $request->reviewer_title,
            'body'           => $request->body,
            'rating'         => $request->rating,
            'movie_id'       => $request->movie_id,
            'sort_order'     => $request->input('sort_order', 0),
            'is_active'      => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('photo')) {
            $this->deleteFile($review->photo);
            $review->photo = $this->uploadFile($request->file('photo'), 'reviews');
        }

        $review->save();
        return back()->with('success', "Review by \"{$review->reviewer_name}\" updated.");
    }

    public function deleteReview(Review $review)
    {
        $this->deleteFile($review->photo);
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    /* ══════════════════════════════════════
       SOFTWARE
    ══════════════════════════════════════ */

    public function storeSoftware(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'role'       => 'nullable|string|max:150',
            'width'      => 'nullable|integer|min:16|max:300',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable|image|max:2048',
        ]);

        $sw = Software::create([
            'name'       => $request->name,
            'role'       => $request->role,
            'width'      => $request->input('width', 48),
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $sw->image = $this->uploadFile($request->file('image'), 'software');
            $sw->save();
        }

        return back()->with('success', "Software \"{$sw->name}\" added.");
    }

    public function updateSoftware(Request $request, Software $software)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'role'       => 'nullable|string|max:150',
            'width'      => 'nullable|integer|min:16|max:300',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
            'image'      => 'nullable|image|max:2048',
        ]);

        $software->fill([
            'name'       => $request->name,
            'role'       => $request->role,
            'width'      => $request->input('width', 48),
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('image')) {
            $this->deleteFile($software->image);
            $software->image = $this->uploadFile($request->file('image'), 'software');
        }

        $software->save();
        return back()->with('success', "Software \"{$software->name}\" updated.");
    }

    public function deleteSoftware(Software $software)
    {
        $this->deleteFile($software->image);
        $software->delete();
        return back()->with('success', 'Software deleted.');
    }
}