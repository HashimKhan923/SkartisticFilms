@extends('layouts.app')

@section('title', $page->title . ' — ' . ($settings['site_name'] ?? 'SK Artistic Films'))

@section('head')
<style>
.page-hero {
    padding: 160px 0 60px;
    background: linear-gradient(to bottom, #0a0a0a, #141414);
    border-bottom: 1px solid rgba(201,168,76,.1);
    text-align: center;
}

.page-hero-label {
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .35em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 14px;
}

.page-hero-title {
    font-family: 'Barlow', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900;
    text-transform: uppercase;
    color: #fff;
    letter-spacing: .02em;
}

.page-body {
    max-width: 860px;
    margin: 0 auto;
    padding: 70px 40px 100px;
}

/* Rich text content styles */
.page-content h2 {
    font-family: 'Barlow', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #fff;
    margin: 40px 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(201,168,76,.15);
}

.page-content h2:first-child { margin-top: 0; }

.page-content h3 {
    font-family: 'Barlow', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--gold);
    margin: 28px 0 10px;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.page-content p {
    font-family: 'Barlow', sans-serif;
    font-size: 15px;
    color: rgba(255,255,255,.6);
    line-height: 1.85;
    margin-bottom: 16px;
}

.page-content ul,
.page-content ol {
    margin: 0 0 16px 24px;
    color: rgba(255,255,255,.6);
    font-family: 'Barlow', sans-serif;
    font-size: 15px;
    line-height: 1.85;
}

.page-content li { margin-bottom: 6px; }

.page-content a {
    color: var(--gold);
    text-decoration: underline;
    text-underline-offset: 3px;
}

.page-content strong { color: #fff; font-weight: 600; }

.page-content blockquote {
    border-left: 3px solid var(--gold);
    padding: 12px 20px;
    margin: 24px 0;
    background: rgba(201,168,76,.04);
    font-style: italic;
    color: rgba(255,255,255,.5);
}

@media (max-width: 600px) {
    .page-body { padding: 40px 20px 70px; }
}
</style>
@endsection

@section('content')

<div class="page-hero">
    <div class="container">
        <p class="page-hero-label">SK Artistic Films</p>
        <h1 class="page-hero-title">{{ $page->title }}</h1>
    </div>
</div>

<div class="page-body">
    <div class="page-content">
        {!! $page->content !!}
    </div>

    <div style="margin-top:50px;padding-top:30px;border-top:1px solid rgba(255,255,255,.06);">
        <a href="{{ route('home') }}"
           style="display:inline-flex;align-items:center;gap:8px;font-family:'Barlow',sans-serif;font-size:13px;font-weight:600;color:var(--gold);letter-spacing:.08em;text-transform:uppercase;">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>
</div>

@endsection