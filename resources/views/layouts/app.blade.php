<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['site_name'] ?? 'SK Artistic Films')</title>

    {{-- Netflix uses "Netflix Sans" internally, closest public match is Bebas for display
         and Barlow / DM Sans for body — very close to Netflix's actual UI font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">

    {{-- Keep Cormorant for section headings elegance --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    @yield('head')

    <style>
        :root {
            --gold:    #c9a84c;
            --gold2:   #e8c97a;
            --dark:    #141414;
            --dark2:   #0e0e0e;
            --dark3:   #181818;
            --dark4:   #222222;
            --gray:    #808080;
            --light:   #e5e5e5;
            --white:   #ffffff;

            /* Netflix-style font stack */
            --ff-head:    'Cormorant Garamond', 'Georgia', serif;
            --ff-body:    'Barlow', 'Helvetica Neue', Arial, sans-serif;
            --ff-netflix: 'Barlow', 'Helvetica Neue', Arial, sans-serif;
            --ff-display: 'Bebas Neue', 'Impact', sans-serif;

            --ease: cubic-bezier(.4,0,.2,1);
            --nav-h: 68px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--dark);
            color: var(--light);
            font-family: var(--ff-body);
            font-weight: 400;
            line-height: 1.5;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--dark); }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        ::selection { background: rgba(201,168,76,.35); color: var(--white); }

        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }

        /* ── Shared utils ── */
        .gold { color: var(--gold); }

        .section { padding: 80px 0; }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 60px;
        }

        .section-label {
            font-family: var(--ff-body);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .35em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }

        .section-title {
            font-family: var(--ff-head);
            font-size: clamp(2.2rem, 4.5vw, 3.8rem);
            font-weight: 400;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .divider {
            width: 48px;
            height: 2px;
            background: var(--gold);
            margin: 20px 0;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            font-family: var(--ff-body);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .06em;
            cursor: pointer;
            border: none;
            transition: all .25s var(--ease);
            border-radius: 3px;
        }

        .btn-gold { background: var(--gold); color: var(--dark); }
        .btn-gold:hover { background: var(--gold2); transform: translateY(-1px); box-shadow: 0 8px 30px rgba(201,168,76,.3); }

        .btn-outline {
            background: transparent;
            color: var(--light);
            border: 1px solid rgba(255,255,255,.25);
        }
        .btn-outline:hover { border-color: var(--gold); color: var(--gold); }

        /* ── Noise ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            opacity: .022;
            pointer-events: none;
            z-index: 9999;
        }

        /* ════════════════════════════════════
           NAVBAR — Netflix exact style
        ════════════════════════════════════ */
        #navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            z-index: 1000;
            display: flex;
            align-items: center;
            /* Netflix gradient: transparent at top, fades to black on scroll */
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,.7) 0%,
                transparent 100%
            );
            transition: background .4s var(--ease);
        }

        /* Scrolled: solid dark like Netflix */
        #navbar.scrolled {
            background: rgba(20,20,20,.97);
        }

        .nav-inner {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 60px;
            display: flex;
            align-items: center;
            gap: 0;
        }

        /* ── Logo — Netflix-style bold red/gold wordmark ── */
        .nav-logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            margin-right: 28px;
            text-decoration: none;
        }

        .nav-logo-img {
            height: 32px;
            width: auto;
        }

        /* Text logo when no image — Netflix-like bold wordmark */
        .nav-logo-wordmark {
            font-family: var(--ff-display);
            font-size: 2rem;
            letter-spacing: .06em;
            color: var(--gold);
            line-height: 1;
            /* Netflix uses a very specific weight — Bebas gives that heavy cinematic look */
        }

        /* ── Primary Nav — Netflix left-side links ── */
        .nav-primary {
            display: flex;
            align-items: center;
            gap: 0;
            list-style: none;
            flex: 1;
        }

        .nav-primary li a {
            display: block;
            padding: 8px 14px;
            font-family: var(--ff-body);
            font-size: 13px;
            font-weight: 400;
            color: rgba(255,255,255,.75);
            transition: color .2s;
            letter-spacing: .01em;
            white-space: nowrap;
        }

        .nav-primary li a:hover { color: var(--white); }

        /* Active / current link */
        .nav-primary li a.active { color: var(--white); font-weight: 600; }

        /* Netflix "Browse by" dropdown style — just the arrow indicator */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown > a {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-dropdown > a::after {
            content: '';
            border: 4px solid transparent;
            border-top-color: currentColor;
            margin-top: 3px;
            transition: transform .2s;
        }

        .nav-dropdown:hover > a::after { transform: rotate(180deg); margin-top: -2px; }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: rgba(0,0,0,.92);
            border: 1px solid rgba(255,255,255,.12);
            border-top: 2px solid var(--white);
            min-width: 180px;
            padding: 8px 0;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-6px);
            transition: all .2s var(--ease);
            backdrop-filter: blur(12px);
            z-index: 100;
        }

        /* Arrow notch on dropdown */
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 20px;
            border: 6px solid transparent;
            border-bottom-color: rgba(255,255,255,.8);
            border-top: none;
        }

        .nav-dropdown:hover .dropdown-menu {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }

        .dropdown-menu a {
            display: block;
            padding: 9px 20px;
            font-size: 13px;
            color: rgba(255,255,255,.75);
            transition: color .15s, background .15s;
            white-space: nowrap;
        }

        .dropdown-menu a:hover {
            color: var(--white);
            background: rgba(255,255,255,.06);
        }

        /* ── Right side icons — Netflix style ── */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }

        /* Search icon */
        .nav-icon-btn {
            background: none;
            border: none;
            color: var(--white);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color .2s, transform .2s;
            position: relative;
        }

        .nav-icon-btn:hover { color: rgba(255,255,255,.75); }

        /* Search expand — Netflix style */
        .nav-search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-search-input {
            width: 0;
            overflow: hidden;
            background: rgba(0,0,0,.7);
            border: 1px solid transparent;
            color: var(--white);
            font-family: var(--ff-body);
            font-size: 13px;
            padding: 0;
            outline: none;
            transition: width .3s var(--ease), padding .3s, border-color .3s;
            height: 32px;
        }

        .nav-search-wrap.open .nav-search-input {
            width: 220px;
            padding: 0 12px;
            border-color: rgba(255,255,255,.6);
        }

        .nav-search-btn {
            background: none;
            border: none;
            color: var(--white);
            font-size: 17px;
            cursor: pointer;
            padding: 4px 6px;
            display: flex;
            align-items: center;
            transition: color .2s;
            z-index: 1;
        }

        /* Notification bell */
        .nav-bell {
            position: relative;
        }

        .nav-bell-dot {
            position: absolute;
            top: 1px; right: 1px;
            width: 7px; height: 7px;
            background: var(--gold);
            border-radius: 50%;
            border: 1px solid var(--dark);
        }

        /* Profile avatar — Netflix-style colored square */
        .nav-profile {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            position: relative;
        }

        .nav-avatar {
            width: 32px; height: 32px;
            border-radius: 4px;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--ff-body);
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: .02em;
            flex-shrink: 0;
        }

        .nav-profile-caret {
            border: 4px solid transparent;
            border-top-color: var(--white);
            margin-top: 3px;
            transition: transform .2s;
        }

        .nav-profile:hover .nav-profile-caret { transform: rotate(180deg); margin-top: -2px; }

        /* Profile dropdown */
        .nav-profile-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background: rgba(0,0,0,.9);
            border: 1px solid rgba(255,255,255,.12);
            border-top: 2px solid var(--white);
            min-width: 180px;
            padding: 8px 0;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-6px);
            transition: all .2s;
            backdrop-filter: blur(12px);
        }

        .nav-profile-dropdown::before {
            content: '';
            position: absolute;
            top: -8px; right: 14px;
            border: 6px solid transparent;
            border-bottom-color: rgba(255,255,255,.8);
            border-top: none;
        }

        .nav-profile:hover .nav-profile-dropdown {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }

        .nav-profile-dropdown a,
        .nav-profile-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 18px;
            font-size: 13px;
            color: rgba(255,255,255,.75);
            background: none;
            border: none;
            cursor: pointer;
            font-family: var(--ff-body);
            transition: color .15s, background .15s;
            text-align: left;
        }

        .nav-profile-dropdown a:hover,
        .nav-profile-dropdown button:hover {
            color: var(--white);
            background: rgba(255,255,255,.06);
        }

        .nav-profile-dropdown .dropdown-divider {
            height: 1px;
            background: rgba(255,255,255,.1);
            margin: 6px 0;
        }

        /* ── Mobile hamburger ── */
        .nav-ham {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            margin-left: auto;
        }

        .nav-ham span {
            display: block;
            width: 22px; height: 1.5px;
            background: var(--white);
            transition: all .3s;
        }

        /* ── Custom Cursor ── */
        .cursor-dot, .cursor-ring {
            position: fixed;
            pointer-events: none;
            z-index: 10000;
            border-radius: 50%;
            transform: translate(-50%,-50%);
        }

        .cursor-dot {
            width: 5px; height: 5px;
            background: var(--white);
            top: 0; left: 0;
        }

        .cursor-ring {
            width: 28px; height: 28px;
            border: 1px solid rgba(255,255,255,.4);
            top: 0; left: 0;
            transition: width .25s, height .25s, border-color .25s;
        }

        .cursor-ring.hovering {
            width: 50px; height: 50px;
            border-color: var(--gold);
        }

        /* ── Social icons ── */
        .social-icon {
            width: 36px; height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 50%;
            font-size: 13px;
            color: rgba(255,255,255,.5);
            transition: all .3s;
        }
        .social-icon:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-2px); }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .container { padding: 0 24px; }
            .nav-inner  { padding: 0 24px; }
            .nav-ham    { display: flex; }
            .nav-primary,
            .nav-right  { display: none; }

            .nav-primary.open {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: var(--nav-h);
                left: 0; right: 0;
                background: rgba(10,10,10,.97);
                padding: 20px 0 30px;
                border-top: 1px solid rgba(255,255,255,.06);
                backdrop-filter: blur(12px);
                gap: 0;
            }

            .nav-primary.open li a { padding: 14px 32px; font-size: 15px; }

            .nav-right.open {
                display: flex;
                position: fixed;
                top: calc(var(--nav-h) + 220px);
                right: 24px;
                gap: 16px;
            }
        }

        @media (max-width: 600px) {
            .section { padding: 60px 0; }
        }
    </style>
</head>
<body>

{{-- Custom Cursor (desktop only) --}}
<div class="cursor-dot"  id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

{{-- ════════════════════════════════════════
     NAVBAR
════════════════════════════════════════ --}}
<header id="navbar">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="nav-logo">
            @if(!empty($settings['logo']))
                <img class="nav-logo-img"
                     src="{{ Storage::url($settings['logo']) }}"
                     alt="{{ $settings['site_name'] ?? 'SK Artistic Films' }}">
            @else
                <span class="nav-logo-wordmark">
                    SK&nbsp;ARTISTIC
                </span>
            @endif
        </a>

        {{-- Primary nav links — Netflix layout --}}
        <ul class="nav-primary" id="navPrimary">
            <li><a href="#hero"  class="active">Home</a></li>
            <li><a href="#about">About</a></li>
            <li class="nav-dropdown">
                <a href="#banners">Films</a>
                <div class="dropdown-menu">
                    <a href="#banners">All Films</a>
                    <a href="#projects">Projects</a>
                    <a href="#characters">Cast &amp; Characters</a>
                </div>
            </li>
            <li><a href="#characters">Cast</a></li>
            <li><a href="#software">Production</a></li>
            <li><a href="#reviews">Reviews</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        {{-- Right side icons --}}
        <div class="nav-right" id="navRight">

            {{-- Search --}}
            <div class="nav-search-wrap" id="navSearchWrap">
                <input type="text" class="nav-search-input" id="navSearchInput" placeholder="Search films, cast…">
                <button class="nav-search-btn" id="navSearchBtn" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            {{-- Bell --}}
            <button class="nav-icon-btn nav-bell" aria-label="Notifications">
                <i class="fas fa-bell" style="font-size:17px;"></i>
                <span class="nav-bell-dot"></span>
            </button>

            {{-- Profile / Admin quick access --}}
            <div class="nav-profile">
                <div class="nav-avatar">SK</div>
                <span class="nav-profile-caret"></span>

                <div class="nav-profile-dropdown">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home" style="font-size:13px;"></i> Home
                    </a>
                    <a href="#about">
                        <i class="fas fa-info-circle" style="font-size:13px;"></i> About
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.login') }}">
                        <i class="fas fa-lock" style="font-size:13px;"></i> Admin Panel
                    </a>
                </div>
            </div>
        </div>

        {{-- Mobile hamburger --}}
        <button class="nav-ham" id="navHam" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

@yield('content')

{{-- ════════════════════════════════════════
     FOOTER
════════════════════════════════════════ --}}
<footer style="background:#000;border-top:1px solid rgba(255,255,255,.06);padding:56px 0 28px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:40px;margin-bottom:48px;">

            {{-- Brand --}}
            <div>
                <div class="nav-logo-wordmark" style="font-size:1.8rem;margin-bottom:14px;">
                    SK&nbsp;ARTISTIC
                </div>
                <p style="font-size:13px;color:rgba(255,255,255,.35);line-height:1.8;max-width:240px;">
                    {{ $settings['site_tagline'] ?? 'Crafting Stories That Last Forever' }}
                </p>
                <div style="display:flex;gap:10px;margin-top:20px;">
                    @if(!empty($settings['facebook']))
                    <a href="{{ $settings['facebook'] }}" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(!empty($settings['instagram']))
                    <a href="{{ $settings['instagram'] }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(!empty($settings['youtube']))
                    <a href="{{ $settings['youtube'] }}" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <p style="font-size:11px;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-bottom:18px;">Navigate</p>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;">
                    @foreach(['hero'=>'Home','about'=>'About Us','banners'=>'Our Films','characters'=>'Cast','contact'=>'Contact'] as $id=>$label)
                    <li>
                        <a href="#{{ $id }}"
                           style="font-size:13px;color:rgba(255,255,255,.4);transition:color .2s;"
                           onmouseover="this.style.color='#e5e5e5'"
                           onmouseout="this.style.color='rgba(255,255,255,.4)'">
                           {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div>
                <p style="font-size:11px;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-bottom:18px;">Services</p>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;">
                    @foreach(['Film Production','Cinematography','Post-Production','VFX & Color Grading','Sound Design'] as $s)
                    <li style="font-size:13px;color:rgba(255,255,255,.4);">{{ $s }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <p style="font-size:11px;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-bottom:18px;">Contact</p>
                @if(!empty($settings['contact_email']))
                <p style="font-size:13px;color:rgba(255,255,255,.4);margin-bottom:8px;">
                    <i class="fas fa-envelope" style="color:var(--gold);margin-right:8px;font-size:11px;"></i>
                    {{ $settings['contact_email'] }}
                </p>
                @endif
                @if(!empty($settings['contact_phone']))
                <p style="font-size:13px;color:rgba(255,255,255,.4);margin-bottom:8px;">
                    <i class="fas fa-phone" style="color:var(--gold);margin-right:8px;font-size:11px;"></i>
                    {{ $settings['contact_phone'] }}
                </p>
                @endif
                @if(!empty($settings['contact_address']))
                <p style="font-size:13px;color:rgba(255,255,255,.4);">
                    <i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:8px;font-size:11px;"></i>
                    {{ $settings['contact_address'] }}
                </p>
                @endif
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <p style="font-size:12px;color:rgba(255,255,255,.2);">
                {{ $settings['footer_text'] ?? '© '.date('Y').' SK Artistic Films. All Rights Reserved.' }}
            </p>
            <div style="display:flex;gap:20px;">
                <span style="font-size:12px;color:rgba(255,255,255,.2);">Privacy Policy</span>
                <span style="font-size:12px;color:rgba(255,255,255,.2);">Terms of Use</span>
                <a href="{{ route('admin.login') }}" style="font-size:12px;color:rgba(255,255,255,.12);transition:color .2s;"
                   onmouseover="this.style.color='rgba(255,255,255,.4)'"
                   onmouseout="this.style.color='rgba(255,255,255,.12)'">Admin</a>
            </div>
        </div>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({ duration: 800, easing: 'ease-out-quart', once: true, offset: 50 });

    /* ── Navbar: transparent → solid on scroll ── */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 80);
    }, { passive: true });

    /* ── Active nav link on scroll ── */
    const sections  = document.querySelectorAll('section[id], div[id]');
    const navLinks  = document.querySelectorAll('.nav-primary a[href^="#"]');

    const navObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(a => {
                    a.classList.toggle('active', a.getAttribute('href') === '#' + id);
                });
            }
        });
    }, { rootMargin: '-40% 0px -55% 0px' });

    sections.forEach(s => navObserver.observe(s));

    /* ── Search expand ── */
    const searchWrap  = document.getElementById('navSearchWrap');
    const searchBtn   = document.getElementById('navSearchBtn');
    const searchInput = document.getElementById('navSearchInput');

    searchBtn.addEventListener('click', e => {
        e.stopPropagation();
        const isOpen = searchWrap.classList.toggle('open');
        if (isOpen) searchInput.focus();
        else searchInput.value = '';
    });

    document.addEventListener('click', e => {
        if (!searchWrap.contains(e.target)) {
            searchWrap.classList.remove('open');
            searchInput.value = '';
        }
    });

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            searchWrap.classList.remove('open');
            searchInput.value = '';
        }
    });

    /* ── Mobile nav ── */
    const hamBtn     = document.getElementById('navHam');
    const navPrimary = document.getElementById('navPrimary');

    hamBtn.addEventListener('click', () => {
        const open = navPrimary.classList.toggle('open');
        // Animate hamburger lines
        const spans = hamBtn.querySelectorAll('span');
        if (open) {
            spans[0].style.cssText = 'transform: translateY(6.5px) rotate(45deg)';
            spans[1].style.opacity = '0';
            spans[2].style.cssText = 'transform: translateY(-6.5px) rotate(-45deg)';
        } else {
            spans.forEach(s => s.style.cssText = '');
        }
    });

    // Close nav on link click
    navPrimary.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            navPrimary.classList.remove('open');
            hamBtn.querySelectorAll('span').forEach(s => s.style.cssText = '');
        });
    });

    /* ── Custom cursor ── */
    const dot  = document.getElementById('cursorDot');
    const ring = document.getElementById('cursorRing');

    if (window.matchMedia('(pointer: fine)').matches) {
        let mx = 0, my = 0, rx = 0, ry = 0;

        document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; }, { passive: true });

        document.querySelectorAll('a, button, .poster-card, .char-card, .project-tile').forEach(el => {
            el.addEventListener('mouseenter', () => ring.classList.add('hovering'));
            el.addEventListener('mouseleave', () => ring.classList.remove('hovering'));
        });

        (function animateCursor() {
            rx += (mx - rx) * 0.13;
            ry += (my - ry) * 0.13;
            dot.style.left  = mx + 'px';
            dot.style.top   = my + 'px';
            ring.style.left = rx + 'px';
            ring.style.top  = ry + 'px';
            requestAnimationFrame(animateCursor);
        })();
    } else {
        dot.style.display  = 'none';
        ring.style.display = 'none';
    }
</script>

@yield('scripts')
</body>
</html>