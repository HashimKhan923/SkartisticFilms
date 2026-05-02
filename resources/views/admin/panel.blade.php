<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — SK Artistic Films</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold:   #c9a84c;
            --dark:   #0a0a0a;
            --dark2:  #111111;
            --dark3:  #1a1a1a;
            --dark4:  #222;
            --border: rgba(255,255,255,.08);
            --text:   #d0d0c8;
            --muted:  rgba(255,255,255,.35);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--dark);
            color: var(--text);
            font-size: 14px;
            min-height: 100vh;
        }

        /* ── Top Bar ── */
        .topbar {
            background: var(--dark2);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
        }

        .topbar-left span { color: var(--gold); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .site-link {
            font-size: 12px;
            color: var(--muted);
            transition: color .2s;
            letter-spacing: .05em;
        }

        .site-link:hover { color: var(--gold); }

        .logout-btn {
            background: none;
            border: 1px solid rgba(255,255,255,.12);
            color: var(--text);
            padding: 7px 18px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            cursor: pointer;
            letter-spacing: .08em;
            transition: all .2s;
        }

        .logout-btn:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        /* ── Layout ── */
        .admin-wrap {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            background: var(--dark2);
            border-right: 1px solid var(--border);
            padding: 24px 0;
            position: sticky;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .3em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 16px 20px 8px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all .2s;
            color: var(--muted);
            font-size: 13px;
        }

        .sidebar-item:hover { background: rgba(255,255,255,.03); color: var(--text); }

        .sidebar-item.active {
            border-color: var(--gold);
            background: rgba(201,168,76,.06);
            color: var(--gold);
        }

        .sidebar-item i { width: 16px; text-align: center; font-size: 13px; }

        /* ── Main ── */
        .main {
            flex: 1;
            padding: 32px;
            overflow-x: hidden;
        }

        /* ── Tabs ── */
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ── Alert ── */
        .alert {
            padding: 14px 18px;
            border-radius: 0;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .alert-success {
            background: rgba(34,197,94,.1);
            border: 1px solid rgba(34,197,94,.3);
            color: #4ade80;
        }

        .alert-error {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.3);
            color: #f87171;
        }

        /* ── Form styles ── */
        .form-section {
            background: var(--dark2);
            border: 1px solid var(--border);
            padding: 28px;
            margin-bottom: 24px;
        }

        .form-section-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-grid.cols-1 { grid-template-columns: 1fr; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }

        .form-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .form-control {
            background: var(--dark3);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 10px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            outline: none;
            transition: border-color .2s;
            width: 100%;
        }

        .form-control:focus { border-color: rgba(201,168,76,.4); }

        textarea.form-control { resize: vertical; min-height: 100px; }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%236b6b6b' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .form-hint {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* File input */
        .file-input-wrap {
            position: relative;
        }

        .file-input-wrap input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: var(--dark3);
            border: 1px dashed rgba(255,255,255,.15);
            cursor: pointer;
            font-size: 12px;
            color: var(--muted);
            transition: border-color .2s;
        }

        .file-input-wrap:hover .file-input-label { border-color: var(--gold); color: var(--gold); }

        .file-preview {
            margin-top: 8px;
            max-width: 120px;
            max-height: 80px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        /* ── Btn ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: all .2s;
        }

        .btn-gold { background: var(--gold); color: #080808; }
        .btn-gold:hover { background: #e8c97a; transform: translateY(-1px); }

        .btn-outline {
            background: none;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-outline:hover { border-color: var(--gold); color: var(--gold); }

        .btn-danger {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.2);
            color: #f87171;
        }

        .btn-danger:hover { background: rgba(239,68,68,.2); }

        .btn-sm { padding: 6px 14px; font-size: 11px; }

        /* ── Table / Cards ── */
        .item-card {
            background: var(--dark2);
            border: 1px solid var(--border);
            padding: 20px;
            margin-bottom: 12px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .item-thumb {
            width: 72px;
            height: 90px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--dark3);
        }

        .item-info { flex: 1; min-width: 0; }

        .item-title {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .item-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* ── Accordion / Edit form ── */
        .edit-form {
            display: none;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .edit-form.open { display: block; }

        /* ── Radio group ── */
        .radio-group {
            display: flex;
            gap: 16px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .radio-option input { accent-color: var(--gold); }

        /* ── Toggle ── */
        .toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text);
        }

        .toggle-label input { accent-color: var(--gold); }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .form-grid { grid-template-columns: 1fr; }
            .form-grid.cols-3 { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

{{-- Top Bar --}}
<div class="topbar">
    <div class="topbar-left">
        <i class="fas fa-film" style="color:var(--gold);"></i>
        SK <span>Artistic</span> Films &nbsp;— Admin Panel
    </div>
    <div class="topbar-right">
        <a href="{{ route('home') }}" target="_blank" class="site-link">
            <i class="fas fa-external-link-alt"></i> View Site
        </a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</div>

<div class="admin-wrap">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <p class="sidebar-label">Content</p>
        <div class="sidebar-item active" onclick="showTab('settings', this)">
            <i class="fas fa-cog"></i> Site Settings
        </div>
        <div class="sidebar-item" onclick="showTab('movies', this)">
            <i class="fas fa-film"></i> Movies
        </div>
        <div class="sidebar-item" onclick="showTab('characters', this)">
            <i class="fas fa-users"></i> Characters
        </div>
        <p class="sidebar-label" style="margin-top:12px;">Media</p>
        <div class="sidebar-item" onclick="showTab('hero', this)">
            <i class="fas fa-photo-video"></i> Hero Banner
        </div>
        <div class="sidebar-item" onclick="showTab('timer', this)">
            <i class="fas fa-clock"></i> Countdown Timer
        </div>
        <div class="sidebar-item" onclick="showTab('banners', this)">
            <i class="fas fa-th-large"></i> 6 Banners
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="main">

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            @foreach($errors->all() as $e){{ $e }}@if(!$loop->last), @endif @endforeach
        </div>
        @endif

        {{-- ═══════════ SETTINGS TAB ═══════════ --}}
        <div class="tab-pane active" id="tab-settings">
            <div class="page-header">
                <h1 class="page-title">Site Settings</h1>
            </div>

            <form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">
                @csrf

                {{-- Branding --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-palette"></i> Branding</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control"
                                value="{{ $settings['site_name'] ?? 'SK Artistic Films' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="site_tagline" class="form-control"
                                value="{{ $settings['site_tagline'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Logo (PNG/JPG)</label>
                            <div class="file-input-wrap">
                                <label class="file-input-label">
                                    <i class="fas fa-upload"></i>
                                    <span id="logo-name">{{ $settings['logo'] ? basename($settings['logo']) : 'Choose file…' }}</span>
                                </label>
                                <input type="file" name="logo" accept="image/*"
                                    onchange="previewFile(this,'logo-preview','logo-name')">
                            </div>
                            @if(!empty($settings['logo']))
                            <img src="{{ Storage::url($settings['logo']) }}" class="file-preview" id="logo-preview">
                            @else
                            <img class="file-preview" id="logo-preview" style="display:none;">
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Founded Year</label>
                            <input type="number" name="founded_year" class="form-control"
                                value="{{ $settings['founded_year'] ?? 2012 }}" min="1900" max="{{ date('Y') }}">
                        </div>
                    </div>
                </div>

                {{-- About Section --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-info-circle"></i> About Section</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">About Title</label>
                            <input type="text" name="about_title" class="form-control"
                                value="{{ $settings['about_title'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">About Image</label>
                            <div class="file-input-wrap">
                                <label class="file-input-label">
                                    <i class="fas fa-image"></i>
                                    <span id="about-img-name">{{ $settings['about_image'] ? basename($settings['about_image']) : 'Choose file…' }}</span>
                                </label>
                                <input type="file" name="about_image" accept="image/*"
                                    onchange="previewFile(this,'about-img-preview','about-img-name')">
                            </div>
                            @if(!empty($settings['about_image']))
                            <img src="{{ Storage::url($settings['about_image']) }}" class="file-preview" id="about-img-preview">
                            @else
                            <img class="file-preview" id="about-img-preview" style="display:none;">
                            @endif
                        </div>
                        <div class="form-group full">
                            <label class="form-label">About Text</label>
                            <textarea name="about_text" class="form-control" rows="4">{{ $settings['about_text'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact & Social --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-address-book"></i> Contact &amp; Social</div>
                    <div class="form-grid cols-3">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="contact_email" class="form-control"
                                value="{{ $settings['contact_email'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="contact_phone" class="form-control"
                                value="{{ $settings['contact_phone'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="contact_address" class="form-control"
                                value="{{ $settings['contact_address'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook" class="form-control"
                                value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/…">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="instagram" class="form-control"
                                value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/…">
                        </div>
                        <div class="form-group">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube" class="form-control"
                                value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/…">
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Footer Text</label>
                            <input type="text" name="footer_text" class="form-control"
                                value="{{ $settings['footer_text'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </form>
        </div>

        {{-- ═══════════ HERO TAB ═══════════ --}}
        <div class="tab-pane" id="tab-hero">
            <div class="page-header">
                <h1 class="page-title">Hero Banner</h1>
            </div>

            <form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-heading"></i> Hero Text</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Hero Title</label>
                            <input type="text" name="hero_title" class="form-control"
                                value="{{ $settings['hero_title'] ?? 'SK Artistic Films' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hero Subtitle</label>
                            <input type="text" name="hero_subtitle" class="form-control"
                                value="{{ $settings['hero_subtitle'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-photo-video"></i> Hero Media</div>

                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label">Media Type</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="hero_type" value="image"
                                    {{ ($settings['hero_type'] ?? 'image') === 'image' ? 'checked' : '' }}
                                    onchange="toggleHeroType()">
                                <span>Image</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="hero_type" value="video_upload"
                                    {{ ($settings['hero_type'] ?? '') === 'video_upload' ? 'checked' : '' }}
                                    onchange="toggleHeroType()">
                                <span>Upload Video</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="hero_type" value="youtube"
                                    {{ ($settings['hero_type'] ?? '') === 'youtube' ? 'checked' : '' }}
                                    onchange="toggleHeroType()">
                                <span>YouTube Link</span>
                            </label>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div id="hero-image-field" class="form-group">
                        <label class="form-label">Hero Image</label>
                        <div class="file-input-wrap">
                            <label class="file-input-label">
                                <i class="fas fa-image"></i>
                                <span id="hero-img-name">{{ $settings['hero_image'] ? basename($settings['hero_image']) : 'Choose image…' }}</span>
                            </label>
                            <input type="file" name="hero_image" accept="image/*"
                                onchange="previewFile(this,'hero-img-preview','hero-img-name')">
                        </div>
                        @if(!empty($settings['hero_image']))
                        <img src="{{ Storage::url($settings['hero_image']) }}" class="file-preview" id="hero-img-preview" style="max-width:200px;max-height:120px;">
                        @else
                        <img class="file-preview" id="hero-img-preview" style="display:none;max-width:200px;max-height:120px;">
                        @endif
                    </div>

                    {{-- Video Upload --}}
                    <div id="hero-video-field" class="form-group" style="display:none;">
                        <label class="form-label">Hero Video (MP4/WebM, max 200MB)</label>
                        <div class="file-input-wrap">
                            <label class="file-input-label">
                                <i class="fas fa-video"></i>
                                <span id="hero-vid-name">{{ $settings['hero_video_file'] ? basename($settings['hero_video_file']) : 'Choose video…' }}</span>
                            </label>
                            <input type="file" name="hero_video_file" accept="video/mp4,video/webm"
                                onchange="document.getElementById('hero-vid-name').textContent = this.files[0]?.name || 'Choose video…'">
                        </div>
                        @if(!empty($settings['hero_video_file']))
                        <p class="form-hint" style="color:var(--gold);">Current: {{ basename($settings['hero_video_file']) }}</p>
                        @endif
                    </div>

                    {{-- YouTube --}}
                    <div id="hero-youtube-field" class="form-group" style="display:none;">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="hero_youtube" class="form-control"
                            value="{{ $settings['hero_youtube'] ?? '' }}"
                            placeholder="https://www.youtube.com/watch?v=…">
                        <p class="form-hint">Paste the full YouTube video URL.</p>
                    </div>

                    {{-- Hidden fields to carry over unrelated settings --}}
                    <input type="hidden" name="site_name"    value="{{ $settings['site_name'] ?? 'SK Artistic Films' }}">
                    <input type="hidden" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}">
                    <input type="hidden" name="about_title"  value="{{ $settings['about_title'] ?? '' }}">
                    <input type="hidden" name="about_text"   value="{{ $settings['about_text'] ?? '' }}">
                    <input type="hidden" name="founded_year" value="{{ $settings['founded_year'] ?? 2012 }}">
                    <input type="hidden" name="contact_email"   value="{{ $settings['contact_email'] ?? '' }}">
                    <input type="hidden" name="contact_phone"   value="{{ $settings['contact_phone'] ?? '' }}">
                    <input type="hidden" name="contact_address" value="{{ $settings['contact_address'] ?? '' }}">
                    <input type="hidden" name="facebook"    value="{{ $settings['facebook'] ?? '' }}">
                    <input type="hidden" name="instagram"   value="{{ $settings['instagram'] ?? '' }}">
                    <input type="hidden" name="youtube"     value="{{ $settings['youtube'] ?? '' }}">
                    <input type="hidden" name="footer_text" value="{{ $settings['footer_text'] ?? '' }}">
                    <input type="hidden" name="hero_title"    value="{{ $settings['hero_title'] ?? '' }}">
                    <input type="hidden" name="hero_subtitle" value="{{ $settings['hero_subtitle'] ?? '' }}">
                </div>

                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Save Hero Settings
                </button>
            </form>
        </div>

        {{-- ═══════════ TIMER TAB ═══════════ --}}
<div class="tab-pane" id="tab-timer">
    <div class="page-header">
        <h1 class="page-title">Countdown Timer</h1>
    </div>

    <form method="POST" action="{{ route('admin.timer.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-section">
            <div class="form-section-title"><i class="fas fa-clock"></i> Timer Settings</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Enable Timer Section</label>
                    <label class="toggle-label">
                        <input type="checkbox" name="timer_active" value="1"
                            {{ ($settings['timer_active'] ?? '0') === '1' ? 'checked' : '' }}>
                        Show countdown section on homepage
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Timer Title</label>
                    <input type="text" name="timer_title" class="form-control"
                        value="{{ $settings['timer_title'] ?? '' }}"
                        placeholder="e.g. New Release Coming Soon">
                </div>
                <div class="form-group">
                    <label class="form-label">Countdown Date &amp; Time</label>
                    <input type="datetime-local" name="timer_date" class="form-control"
                        value="{{ $settings['timer_date'] ?? '' }}">
                    <p class="form-hint">The timer counts down to this date.</p>
                </div>
                <div class="form-group full">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="timer_subtitle" class="form-control"
                        value="{{ $settings['timer_subtitle'] ?? '' }}"
                        placeholder="e.g. Mark your calendar — the wait is almost over">
                </div>
                <div class="form-group">
                    <label class="form-label">Background Image</label>
                    <div class="file-input-wrap">
                        <label class="file-input-label">
                            <i class="fas fa-image"></i>
                            <span>{{ !empty($settings['timer_image']) ? basename($settings['timer_image']) : 'Choose image…' }}</span>
                        </label>
                        <input type="file" name="timer_image" accept="image/*"
                            onchange="previewFile(this,'timer-img-preview','')">
                    </div>
                    @if(!empty($settings['timer_image']))
                    <img src="{{ Storage::url($settings['timer_image']) }}" class="file-preview" id="timer-img-preview" style="max-width:200px;max-height:100px;margin-top:8px;">
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-gold">
            <i class="fas fa-save"></i> Save Timer Settings
        </button>
    </form>
</div>

{{-- ═══════════ BANNERS TAB ═══════════ --}}
<div class="tab-pane" id="tab-banners">
    <div class="page-header">
        <h1 class="page-title">6 Vertical Banners</h1>
        <button class="btn btn-gold btn-sm" onclick="toggleAddForm('add-banner-form')">
            <i class="fas fa-plus"></i> Add Banner
        </button>
    </div>

    <p style="font-size:12px;color:var(--muted);margin-bottom:20px;">
        These 6 banners appear in the "Only on SK Artistic Films" section. Upload tall portrait images (2:3 ratio). Any empty slots auto-fill with movies.
    </p>

    {{-- Add Banner --}}
    <div class="form-section" id="add-banner-form" style="display:none;">
        <div class="form-section-title"><i class="fas fa-plus-circle"></i> Add New Banner</div>
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="Film or series title">
                </div>
                <div class="form-group">
                    <label class="form-label">Genre / Label</label>
                    <input type="text" name="genre" class="form-control" placeholder="e.g. Drama, Action">
                </div>
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Active</label>
                    <label class="toggle-label">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Show this banner
                    </label>
                </div>
                <div class="form-group full">
                    <label class="form-label">Poster Image (2:3 portrait ratio recommended)</label>
                    <div class="file-input-wrap">
                        <label class="file-input-label">
                            <i class="fas fa-image"></i>
                            <span>Choose portrait image…</span>
                        </label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                </div>
            </div>
            <div style="margin-top:18px;">
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Add Banner</button>
            </div>
        </form>
    </div>

    {{-- Banner list --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
        @forelse($banners as $banner)
        <div style="background:var(--dark2);border:1px solid var(--border);overflow:hidden;border-radius:2px;">
            @if($banner->image)
            <img src="{{ Storage::url($banner->image) }}"
                 style="width:100%;aspect-ratio:2/3;object-fit:cover;display:block;">
            @else
            <div style="width:100%;aspect-ratio:2/3;background:var(--dark3);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-image" style="font-size:2rem;color:rgba(255,255,255,.08);"></i>
            </div>
            @endif

            <div style="padding:12px;">
                <p style="font-size:13px;font-weight:500;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $banner->title }}</p>
                @if($banner->genre)<p style="font-size:11px;color:var(--muted);margin-bottom:8px;">{{ $banner->genre }}</p>@endif

                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
                    <button class="btn btn-outline btn-sm" onclick="toggleEditForm('edit-banner-{{ $banner->id }}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form method="POST" action="{{ route('admin.banners.delete', $banner) }}"
                        onsubmit="return confirm('Delete this banner?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>

                <div class="edit-form" id="edit-banner-{{ $banner->id }}">
                    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <input type="text" name="title" class="form-control" value="{{ $banner->title }}" placeholder="Title" required>
                            <input type="text" name="genre" class="form-control" value="{{ $banner->genre }}" placeholder="Genre">
                            <input type="number" name="sort_order" class="form-control" value="{{ $banner->sort_order }}" placeholder="Order">
                            <label class="toggle-label" style="font-size:12px;">
                                <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}>
                                Active
                            </label>
                            <div class="file-input-wrap">
                                <label class="file-input-label" style="font-size:11px;">
                                    <i class="fas fa-image"></i> New image
                                </label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-gold btn-sm"><i class="fas fa-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:var(--muted);">
            <i class="fas fa-th-large" style="font-size:2rem;display:block;margin-bottom:12px;"></i>
            No banners yet. Add up to 6 portrait banners.
        </div>
        @endforelse
    </div>
</div>

        {{-- ═══════════ MOVIES TAB ═══════════ --}}
        <div class="tab-pane" id="tab-movies">
            <div class="page-header">
                <h1 class="page-title">Movies</h1>
                <button class="btn btn-gold btn-sm" onclick="toggleAddForm('add-movie-form')">
                    <i class="fas fa-plus"></i> Add Movie
                </button>
            </div>

            {{-- Add Movie Form --}}
            <div class="form-section" id="add-movie-form" style="display:none;">
                <div class="form-section-title"><i class="fas fa-plus-circle"></i> Add New Movie</div>
                <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin._movie_form', ['movie' => null])
                    <div style="margin-top:20px;">
                        <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Add Movie</button>
                    </div>
                </form>
            </div>

            {{-- Movie List --}}
            @forelse($movies as $movie)
            <div class="item-card">
                @if($movie->poster)
                <img src="{{ Storage::url($movie->poster) }}" class="item-thumb" alt="{{ $movie->title }}">
                @else
                <div class="item-thumb" style="display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-film" style="color:rgba(255,255,255,.1);font-size:1.5rem;"></i>
                </div>
                @endif

                <div class="item-info">
                    <div class="item-title">{{ $movie->title }}</div>
                    <div class="item-meta">
                        {{ $movie->year ?? '—' }}
                        @if($movie->genre) · {{ $movie->genre }}@endif
                        @if($movie->rating) · ⭐ {{ $movie->rating }}@endif
                        @if($movie->is_featured) · <span style="color:var(--gold);">Featured</span>@endif
                    </div>
                    <div class="item-actions">
                        <button class="btn btn-outline btn-sm" onclick="toggleEditForm('edit-movie-{{ $movie->id }}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.movies.delete', $movie) }}"
                            onsubmit="return confirm('Delete "{{ addslashes($movie->title) }}"?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </div>

                    {{-- Inline Edit --}}
                    <div class="edit-form" id="edit-movie-{{ $movie->id }}">
                        <form method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data">
                            @csrf
                            @include('admin._movie_form', ['movie' => $movie])
                            <div style="margin-top:20px;">
                                <button type="submit" class="btn btn-gold btn-sm"><i class="fas fa-save"></i> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px;color:var(--muted);">
                <i class="fas fa-film" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                No movies yet. Add your first film!
            </div>
            @endforelse
        </div>

        {{-- ═══════════ CHARACTERS TAB ═══════════ --}}
        <div class="tab-pane" id="tab-characters">
            <div class="page-header">
                <h1 class="page-title">Characters</h1>
                <button class="btn btn-gold btn-sm" onclick="toggleAddForm('add-char-form')">
                    <i class="fas fa-plus"></i> Add Character
                </button>
            </div>

            {{-- Add Character Form --}}
            <div class="form-section" id="add-char-form" style="display:none;">
                <div class="form-section-title"><i class="fas fa-user-plus"></i> Add New Character</div>
                <form method="POST" action="{{ route('admin.characters.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin._character_form', ['character' => null, 'movies' => $movies])
                    <div style="margin-top:20px;">
                        <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Add Character</button>
                    </div>
                </form>
            </div>

            {{-- Character List --}}
            @forelse($characters as $char)
            <div class="item-card">
                @if($char->photo)
                <img src="{{ Storage::url($char->photo) }}" class="item-thumb" alt="{{ $char->name }}"
                    style="aspect-ratio:3/4;width:72px;height:90px;object-position:top;">
                @else
                <div class="item-thumb" style="display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:rgba(255,255,255,.1);">
                    {{ strtoupper(substr($char->name, 0, 1)) }}
                </div>
                @endif

                <div class="item-info">
                    <div class="item-title">{{ $char->name }}</div>
                    <div class="item-meta">
                        @if($char->role){{ $char->role }}@endif
                        @if($char->actor_name) · {{ $char->actor_name }}@endif
                        @if($char->movie) · <span style="color:var(--gold);">{{ $char->movie->title }}</span>@endif
                    </div>
                    <div class="item-actions">
                        <button class="btn btn-outline btn-sm" onclick="toggleEditForm('edit-char-{{ $char->id }}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.characters.delete', $char) }}"
                            onsubmit="return confirm('Delete {{ addslashes($char->name) }}?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </div>

                    <div class="edit-form" id="edit-char-{{ $char->id }}">
                        <form method="POST" action="{{ route('admin.characters.update', $char) }}" enctype="multipart/form-data">
                            @csrf
                            @include('admin._character_form', ['character' => $char, 'movies' => $movies])
                            <div style="margin-top:20px;">
                                <button type="submit" class="btn btn-gold btn-sm"><i class="fas fa-save"></i> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:60px;color:var(--muted);">
                <i class="fas fa-users" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                No characters yet.
            </div>
            @endforelse
        </div>

    </main>
</div>

<script>
function showTab(id, el) {
    document.querySelectorAll('.tab-pane').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sidebar-item').forEach(s => s.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    el.classList.add('active');
}

function toggleAddForm(id) {
    const f = document.getElementById(id);
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function toggleEditForm(id) {
    const f = document.getElementById(id);
    f.classList.toggle('open');
}

function toggleHeroType() {
    const val = document.querySelector('input[name="hero_type"]:checked')?.value;
    document.getElementById('hero-image-field').style.display  = val === 'image'        ? 'flex' : 'none';
    document.getElementById('hero-video-field').style.display  = val === 'video_upload' ? 'flex' : 'none';
    document.getElementById('hero-youtube-field').style.display = val === 'youtube'      ? 'flex' : 'none';
}

// File preview
function previewFile(input, previewId, nameId) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById(nameId).textContent = file.name;
    const preview = document.getElementById(previewId);
    if (preview && file.type.startsWith('image/')) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}

function toggleMovieVideo(uid, type) {
    var yt   = document.getElementById('mv-yt-' + uid);
    var file = document.getElementById('mv-file-' + uid);
    if (yt)   yt.style.display   = type === 'youtube' ? 'flex' : 'none';
    if (file) file.style.display = type === 'upload'  ? 'flex' : 'none';
}

// Init hero type toggle
toggleHeroType();
</script>
</body>
</html>