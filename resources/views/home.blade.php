@extends('layouts.app')

@section('head')
<style>
/* ═══════════════════════════════════════
   GLOBAL — Netflix font overrides
═══════════════════════════════════════ */
body {
    font-family: 'Barlow', 'Helvetica Neue', Arial, sans-serif !important;
    background: #141414;
}

/* ═══════════════════════════════════════
   HERO — Netflix exact layout fix
   Key fix: padding-top = navbar height so
   content is never hidden behind nav
═══════════════════════════════════════ */
#hero {
    position: relative;
    width: 100%;
    height: 100svh;
    min-height: 600px;
    overflow: hidden;
    display: flex;
    align-items: center;      /* vertically center content */
}

.hero-media {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-media img,
.hero-media video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
}

.hero-media iframe {
    position: absolute;
    top: 50%; left: 50%;
    width: 177.78vh;
    min-width: 100%;
    height: 56.25vw;
    min-height: 100%;
    transform: translate(-50%, -50%);
    border: none;
    pointer-events: none;
}

/* Netflix gradient: heavy on left, fades right, dark at bottom */
.hero-gradient {
    position: absolute;
    inset: 0;
    z-index: 1;
    background:
        linear-gradient(77deg, rgba(0,0,0,.85) 0%, transparent 85%),
        linear-gradient(to top, rgba(20,20,20,1) 0%, rgba(20,20,20,.5) 15%, transparent 40%),
        linear-gradient(to bottom, rgba(20,20,20,.6) 0%, transparent 20%);
}

/* Content sits above gradient, padded from top by navbar height */
.hero-content-wrap {
    position: relative;
    z-index: 3;
    width: 100%;
    padding-top: 68px; /* exact navbar height — prevents hiding */
}

.hero-content {
    max-width: 580px;
    padding: 0 60px;
}

/* Studio tag */
.hero-studio-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--gold);
}

.hero-studio-tag::before {
    content: '';
    width: 3px;
    height: 16px;
    background: var(--gold);
    display: block;
    flex-shrink: 0;
}

/* HUGE hero title — Netflix style */
.hero-movie-title {
    font-family: 'Barlow',sans-serif;
    font-size: clamp(4.5rem, 5vw, 9rem);
    font-weight: 700;
    line-height: .92;
    letter-spacing: -.01em;
    color: #fff;
    margin-bottom: 14px;
    text-shadow: 2px 2px 30px rgba(0,0,0,.4);
}

.hero-tagline {
    font-family: 'Barlow', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
    letter-spacing: .01em;
}

.hero-desc {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 400;
    color: rgba(255,255,255,.65);
    line-height: 1.65;
    max-width: 460px;
    margin-bottom: 20px;
}

/* Netflix badge pills */
.hero-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.hero-badge {
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .1em;
    padding: 4px 10px;
    border: 1px solid rgba(255,255,255,.3);
    color: rgba(255,255,255,.75);
    text-transform: uppercase;
}

/* Netflix Play / More Info buttons */
.hero-actions {
     display: flex;
      gap: 10px;
       flex-wrap: wrap;
       margin-bottom: 30px;
     }

.btn-nf-play {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 13px 32px;
    background: #fff;
    color: #000;
    font-family: 'Barlow', sans-serif;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background .15s;
    letter-spacing: .02em;
}

.btn-nf-play:hover { background: rgba(255,255,255,.75); }

.btn-nf-info {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 13px 28px;
    background: rgba(109,109,110,.65);
    color: #fff;
    font-family: 'Barlow', sans-serif;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background .15s;
    backdrop-filter: blur(4px);
    letter-spacing: .02em;
}

.btn-nf-info:hover { background: rgba(109,109,110,.45); }

/* Rating — right edge */
.hero-rating {
    position: absolute;
    right: 0;
    bottom: 28%;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 3px solid rgba(255,255,255,.55);
    padding: 6px 20px 6px 16px;
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,.65);
    letter-spacing: .06em;
}

/* Bottom scroll fade into dark */
.hero-bottom-fade {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 140px;
    background: linear-gradient(to top, #141414 0%, transparent 100%);
    z-index: 2;
}

/* ═══════════════════════════════════════
   TICKER
═══════════════════════════════════════ */
.ticker-wrap {
    overflow: hidden;
    background: var(--gold);
    padding: 12px 0;
}

.ticker-inner {
    display: flex;
    width: max-content;
    animation: tickerScroll 40s linear infinite;
}

.ticker-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 32px;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1rem;
    font-style: italic;
    color: #141414;
    white-space: nowrap;
}

.ticker-dot {
    width: 4px; height: 4px;
    border-radius: 50%;
    background: rgba(20,20,20,.4);
    flex-shrink: 0;
}

@keyframes tickerScroll {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

/* ═══════════════════════════════════════
   COUNTDOWN TIMER
═══════════════════════════════════════ */
#countdown-section {
    position: relative;
    overflow: hidden;
    min-height: 420px;
    display: flex;
    align-items: center;
}

.countdown-bg {
    position: absolute;
    inset: 0;
}

.countdown-bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(.35);
}

.countdown-bg-fallback {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #0a0a0a 0%, #1c140a 50%, #0a0a0a 100%);
}

/* Side gradient */
.countdown-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to right, rgba(20,20,20,.9) 0%, transparent 50%, rgba(20,20,20,.9) 100%),
        linear-gradient(to top, rgba(20,20,20,.8) 0%, transparent 60%);
}

.countdown-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 70px 20px;
    width: 100%;
}

.countdown-eyebrow {
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .4em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 14px;
}

.countdown-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(2rem, 5vw, 3.8rem);
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
    line-height: 1.1;
}

.countdown-subtitle {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    color: rgba(255,255,255,.5);
    margin-bottom: 40px;
    letter-spacing: .03em;
}

/* Clock boxes */
.countdown-clock {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.clock-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.clock-box {
    display: flex;
    gap: 4px;
}

.clock-digit {
    width: 64px;
    height: 80px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Barlow', sans-serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0;
    position: relative;
    overflow: hidden;
    /* Flip animation */
    transition: all .1s;
}

/* Horizontal separator line on digit */
.clock-digit::after {
    content: '';
    position: absolute;
    left: 0; right: 0;
    top: 50%;
    height: 1px;
    background: rgba(0,0,0,.3);
}

.clock-label {
    font-family: 'Barlow', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .25em;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
}

.clock-sep {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--gold);
    margin-top: 12px;
    line-height: 1;
    align-self: flex-start;
}

/* Expired state */
.countdown-expired {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2rem;
    font-style: italic;
    color: var(--gold);
}

/* ═══════════════════════════════════════
   6 VERTICAL BANNERS — Netflix Exact
═══════════════════════════════════════ */
#banners {
    background: #141414;
    padding: 60px 0 70px;
}

.banners-row-header {
    padding: 0 60px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.banners-row-title {
    font-family: 'Barlow', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: #e5e5e5;
    letter-spacing: .01em;
}

.banners-row-title span {
    color: var(--gold);
    margin-right: 6px;
    font-style: italic;
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
}

/* The 6-poster fixed grid — Netflix style */
.banners-six-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px;
    padding: 0 60px;
}

/* Each poster */
.nf-poster {
    position: relative;
    overflow: hidden;
    border-radius: 4px;
    aspect-ratio: 2/3;
    cursor: pointer;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
    flex-shrink: 0;
}

.nf-poster:hover {
    transform: scale(1.05) translateY(-4px);
    box-shadow: 0 20px 50px rgba(0,0,0,.8);
    z-index: 5;
}

.nf-poster-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: filter .3s;
}

.nf-poster:hover .nf-poster-img { filter: brightness(1.12); }

/* Placeholder when no image */
.nf-poster-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1a1a1a 0%, #222 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

/* Bottom overlay with title */
.nf-poster-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top,
        rgba(0,0,0,.96) 0%,
        rgba(0,0,0,.5) 30%,
        transparent 60%
    );
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 14px 12px;
    opacity: 1;
}

.nf-poster-genre {
    font-family: 'Barlow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 4px;
}

.nf-poster-title {
    font-family: 'Barlow', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    line-height: 1.1;
    color: #fff;
    text-shadow: 0 2px 8px rgba(0,0,0,.8);
}

/* Hover play btn top-right */
.nf-poster-playbtn {
    position: absolute;
    top: 10px; right: 10px;
    width: 34px; height: 34px;
    border-radius: 50%;
    background: rgba(255,255,255,.85);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    color: #000;
    opacity: 0;
    transform: scale(.8);
    transition: opacity .25s, transform .25s;
}

.nf-poster:hover .nf-poster-playbtn {
    opacity: 1;
    transform: scale(1);
}

/* ═══════════════════════════════════════
   ABOUT
═══════════════════════════════════════ */
#about {
    background: #0e0e0e;
    position: relative;
    overflow: hidden;
}

#about::before {
    content: 'FILM';
    position: absolute;
    right: -30px; top: 50%;
    transform: translateY(-50%);
    font-family: 'Bebas Neue', sans-serif;
    font-size: 20vw;
    color: rgba(255,255,255,.012);
    pointer-events: none;
    user-select: none;
    line-height: 1;
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.about-img-wrap {
    position: relative;
}

.about-img-wrap img {
    width: 100%;
    aspect-ratio: 4/5;
    object-fit: cover;
}

.about-img-wrap::before {
    content: '';
    position: absolute;
    inset: -14px -14px 14px 14px;
    border: 1px solid rgba(201,168,76,.18);
    z-index: -1;
}

.about-badge {
    position: absolute;
    bottom: -18px; right: -18px;
    background: var(--gold);
    color: #141414;
    padding: 20px 26px;
    text-align: center;
}

.about-badge .num {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 3rem;
    line-height: 1;
    display: block;
}

.about-badge .lbl {
    font-family: 'Barlow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    margin-top: 4px;
    display: block;
}

/* ═══════════════════════════════════════
   CHARACTERS — horizontal scroll
═══════════════════════════════════════ */
#characters { background: #141414; }

.chars-row-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 24px;
}

.chars-scroll-wrap { position: relative; overflow: hidden; }

.chars-scroll-wrap::after {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0;
    width: 80px;
    background: linear-gradient(to left, #141414, transparent);
    pointer-events: none;
    z-index: 2;
}

.chars-track {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding-bottom: 4px;
    cursor: grab;
    scroll-snap-type: x mandatory;
}

.chars-track::-webkit-scrollbar { display: none; }
.chars-track.grabbing { cursor: grabbing; }

.char-card {
    flex-shrink: 0;
    width: 185px;
    scroll-snap-align: start;
}

.char-photo {
    position: relative;
    overflow: hidden;
    border-radius: 3px;
}

.char-photo img {
    width: 100%;
    aspect-ratio: 3/4;
    object-fit: cover;
    object-position: top;
    display: block;
    filter: grayscale(15%);
    transition: filter .35s, transform .35s;
}

.char-card:hover .char-photo img {
    filter: grayscale(0%);
    transform: scale(1.04);
}

.char-photo::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(20,20,20,.85) 0%, transparent 50%);
}

.char-placeholder {
    width: 100%;
    aspect-ratio: 3/4;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: 5rem;
    font-weight: 300;
    color: rgba(255,255,255,.05);
}

.char-info { padding: 12px 0 6px; }

.char-role {
    font-family: 'Barlow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 3px;
}

.char-name {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #e5e5e5;
    margin-bottom: 2px;
}

.char-actor {
    font-family: 'Barlow', sans-serif;
    font-size: 12px;
    color: rgba(255,255,255,.35);
}

/* ═══════════════════════════════════════
   SCROLL ARROWS (shared)
═══════════════════════════════════════ */
.scroll-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: rgba(20,20,20,.88);
    border: 1px solid rgba(255,255,255,.12);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all .2s;
    backdrop-filter: blur(8px);
}

.scroll-arrow:hover { background: rgba(40,40,40,.95); border-color: var(--gold); color: var(--gold); }
.scroll-arrow.left  { left: 4px; }
.scroll-arrow.right { right: 4px; }

/* ═══════════════════════════════════════
   DISCOVER PROJECTS
═══════════════════════════════════════ */
#projects { background: #0e0e0e; }

.projects-mosaic {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    grid-template-rows: 260px 260px;
    gap: 4px;
}

.project-tile {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.project-tile:first-child { grid-row: span 2; }

.project-tile img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .5s, filter .5s;
    filter: brightness(.6);
}

.project-tile:hover img { transform: scale(1.06); filter: brightness(.8); }

.project-tile-info {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.92) 0%, transparent 55%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
}

.project-tile-genre {
    font-family: 'Barlow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 5px;
}

.project-tile-title {
    font-family: 'Barlow', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: #fff;
}

/* ═══════════════════════════════════════
   SOFTWARE
═══════════════════════════════════════ */
#software {
    background: #141414;
    border-top: 1px solid rgba(201,168,76,.07);
    border-bottom: 1px solid rgba(201,168,76,.07);
}

.software-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1px;
    background: rgba(255,255,255,.04);
}

.sw-item {
    background: #141414;
    padding: 36px 20px;
    text-align: center;
    transition: background .25s;
}

.sw-item:hover { background: rgba(201,168,76,.05); }

.sw-icon {
    width: 48px; height: 48px;
    margin: 0 auto 12px;
    border: 1px solid rgba(201,168,76,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    font-size: 1.3rem;
    transition: all .25s;
}

.sw-item:hover .sw-icon { background: rgba(201,168,76,.08); border-color: var(--gold); }

.sw-name {
    font-family: 'Barlow', sans-serif;
    font-size: 12px;
    font-weight: 600;
    color: #e5e5e5;
    margin-bottom: 3px;
}

.sw-role {
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    color: rgba(255,255,255,.3);
}

/* ═══════════════════════════════════════
   REVIEWS
═══════════════════════════════════════ */
#reviews { background: #0e0e0e; }

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 18px;
}

.review-card {
    background: #181818;
    border: 1px solid rgba(255,255,255,.05);
    padding: 28px 24px;
    position: relative;
    transition: border-color .25s, transform .25s;
    border-radius: 3px;
}

.review-card:hover { border-color: rgba(201,168,76,.2); transform: translateY(-3px); }

.review-card::before {
    content: '"';
    font-family: 'Cormorant Garamond', serif;
    font-size: 7rem;
    color: rgba(201,168,76,.1);
    position: absolute;
    top: 2px; left: 14px;
    line-height: 1;
}

.review-stars { display: flex; gap: 3px; margin-bottom: 14px; }
.review-stars i { font-size: 11px; color: var(--gold); }

.review-text {
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
    font-style: italic;
    color: rgba(255,255,255,.55);
    line-height: 1.8;
    margin-bottom: 22px;
    position: relative;
    z-index: 1;
}

.review-author { display: flex; align-items: center; gap: 12px; }

.review-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: rgba(201,168,76,.12);
    border: 1px solid rgba(201,168,76,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--gold);
    flex-shrink: 0;
}

.review-name {
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: #e5e5e5;
}

.review-role {
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    color: rgba(255,255,255,.3);
    margin-top: 1px;
}

/* ═══════════════════════════════════════
   CONTACT
═══════════════════════════════════════ */
#contact { background: #141414; }

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 80px;
    align-items: start;
}

.contact-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 24px;
}

.contact-icon-box {
    width: 42px; height: 42px;
    border: 1px solid rgba(201,168,76,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    font-size: 14px;
    flex-shrink: 0;
}

.contact-item-label {
    font-family: 'Barlow', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: rgba(255,255,255,.3);
    margin-bottom: 3px;
}

.contact-item-val {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    color: #e5e5e5;
}

.cf-field {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    color: #e5e5e5;
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    outline: none;
    border-radius: 2px;
    transition: border-color .2s;
}

.cf-field:focus { border-color: rgba(201,168,76,.35); }
textarea.cf-field { resize: none; }

/* ═══════════════════════════════════════
   NETFLIX MOVIE MODAL
═══════════════════════════════════════ */
.nf-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.75);
    z-index: 8000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
    backdrop-filter: blur(6px);
}

.nf-modal-backdrop.open { opacity: 1; pointer-events: all; }

.nf-modal {
    width: 100%;
    max-width: 860px;
    max-height: 90vh;
    overflow-y: auto;
    background: #181818;
    border-radius: 8px;
    position: relative;
    transform: scale(.94);
    transition: transform .3s;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.15) transparent;
}

.nf-modal-backdrop.open .nf-modal { transform: scale(1); }

.nf-modal-hero {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    background: #111;
}

.nf-modal-hero img,
.nf-modal-hero video {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    border: none;
}

.nf-modal-hero iframe {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    border: none;
}

.nf-modal-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #181818 0%, transparent 55%);
}

.nf-modal-hero-content {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 0 36px 28px;
    z-index: 2;
}

.nf-modal-title {
    font-family: 'Barlow', sans-serif;
    font-size: clamp(2.2rem, 6vw, 4rem);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .02em;
    color: #fff;
    margin-bottom: 16px;
    line-height: .95;
}

.nf-modal-close {
    position: absolute;
    top: 16px; right: 16px;
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(30,30,30,.9);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    z-index: 10;
    transition: all .2s;
}

.nf-modal-close:hover { background: #333; }

.nf-modal-body {
    padding: 10px 36px 36px;
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 32px;
}

.nf-meta-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.nf-meta-year { color: #46d369; font-weight: 600; font-family: 'Barlow', sans-serif; font-size: 13px; }
.nf-meta-dur  { color: rgba(255,255,255,.6); font-family: 'Barlow', sans-serif; font-size: 13px; }
.nf-meta-badge {
    border: 1px solid rgba(255,255,255,.4);
    padding: 1px 6px;
    font-family: 'Barlow', sans-serif;
    font-size: 11px;
    color: rgba(255,255,255,.6);
    border-radius: 2px;
}

.nf-modal-desc {
    font-family: 'Barlow', sans-serif;
    font-size: 14px;
    color: rgba(255,255,255,.7);
    line-height: 1.7;
}

.nf-detail-row {
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
    color: rgba(255,255,255,.45);
    margin-bottom: 10px;
    line-height: 1.7;
}

.nf-detail-row b { color: rgba(255,255,255,.8); font-weight: 500; }

/* ═══════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════ */
@media (max-width: 1100px) {
    .banners-six-grid { grid-template-columns: repeat(3,1fr); }
    .software-row { grid-template-columns: repeat(3,1fr); }
}

@media (max-width: 900px) {
    .hero-content { padding: 0 24px; }
    .banners-row-header { padding: 0 24px; }
    .banners-six-grid { grid-template-columns: repeat(2,1fr); padding: 0 24px; gap: 8px; }
    .about-grid,
    .contact-grid { grid-template-columns: 1fr; gap: 48px; }
    .reviews-grid { grid-template-columns: 1fr; }
    .projects-mosaic { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
    .project-tile:first-child { grid-row: span 1; }
    .nf-modal-body { grid-template-columns: 1fr; }
    .software-row { grid-template-columns: repeat(2,1fr); }
}

@media (max-width: 600px) {
    .banners-six-grid { grid-template-columns: repeat(2,1fr); gap: 5px; }
    .clock-digit { width: 48px; height: 64px; font-size: 2rem; }
}
</style>
@endsection

@section('content')

{{-- ═══════════ HERO ═══════════ --}}
<section id="hero">
    {{-- Background media --}}
    <div class="hero-media">
        @php $ht = $settings['hero_type'] ?? 'image'; @endphp

        @if($ht === 'youtube' && !empty($settings['hero_youtube']))
            @php
                preg_match('/(?:v=|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $settings['hero_youtube'], $ym);
                $ytId = $ym[1] ?? '';
            @endphp
            @if($ytId)
            <iframe
                src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3"
                allow="autoplay; encrypted-media">
            </iframe>
            @endif

        @elseif($ht === 'video_upload' && !empty($settings['hero_video_file']))
            <video autoplay muted loop playsinline preload="auto">
                <source src="{{  asset('uploads/' . $settings['hero_video_file']) }}" type="video/mp4">
            </video>

        @elseif(!empty($settings['hero_image']))
            <img src="{{ asset('uploads/' . $settings['hero_image']) }}"
                 alt="{{ $settings['site_name'] ?? 'SK Artistic Films' }}">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#090909 0%,#1a1208 100%);"></div>
        @endif
    </div>

    {{-- Gradient overlay --}}
    <div class="hero-gradient"></div>

    {{-- Content — padded down by navbar height --}}
    <div class="hero-content-wrap">
        <div class="hero-content">
            <div class="hero-studio-tag">SK Artistic Films</div>

            <h1 class="hero-movie-title">
                {!! nl2br(e($settings['hero_title'] ?? "SK\nArtistic\nFilms")) !!}
            </h1>

            @if(!empty($settings['hero_subtitle']))
            <p class="hero-tagline">{{ $settings['hero_subtitle'] }}</p>
            @endif

            @if($featured)
            <p class="hero-desc">{{ Str::limit($featured->description ?? '', 160) }}</p>
            <div class="hero-badges">
                @if($featured->year)    <span class="hero-badge">{{ $featured->year }}</span>@endif
                @if($featured->genre)   <span class="hero-badge">{{ $featured->genre }}</span>@endif
                @if($featured->rating)  <span class="hero-badge"><i class="fas fa-star" style="color:var(--gold);font-size:9px;"></i> {{ $featured->rating }}</span>@endif
            </div>
            @endif

            <div class="hero-actions">
                @if($featured && ($featured->video_youtube || $featured->video_file))
                <button class="btn-nf-play" onclick="openNfModal({{ $featured->id }})">
                    <i class="fas fa-play"></i> Play
                </button>
                @endif
                @if($featured)
                <button class="btn-nf-info" onclick="openNfModal({{ $featured->id }})">
                    <i class="fas fa-info-circle"></i> More Info
                </button>
                @endif
                @if(!$featured)
                <a href="#banners" class="btn-nf-play">
                    <i class="fas fa-film"></i> Explore Films
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Rating right --}}
    @if($featured && $featured->rating)
    <div class="hero-rating">{{ $featured->rating }}/10</div>
    @endif

    <div class="hero-bottom-fade"></div>
</section>

{{-- ═══════════ TICKER ═══════════ --}}
<div class="ticker-wrap" aria-hidden="true">
    <div class="ticker-inner">
        @php
            $ti = ['Award Winning Films','Cinematic Excellence','Authentic Storytelling','Visual Mastery','Creative Vision','Artistic Direction','Professional Production','Compelling Narratives'];
            $ti = array_merge($ti, $ti);
        @endphp
        @foreach($ti as $t)
        <span class="ticker-item">{{ $t }}<span class="ticker-dot"></span></span>
        @endforeach
    </div>
</div>

{{-- ═══════════ COUNTDOWN TIMER ═══════════ --}}
@if(($settings['timer_active'] ?? '0') === '1' && !empty($settings['timer_date']))
<section id="countdown-section">
    <div class="countdown-bg">
        @if(!empty($settings['timer_image']))
            <img src="{{  asset('uploads/' . $settings['timer_image']) }}" alt="Countdown Banner">
        @else
            <div class="countdown-bg-fallback"></div>
        @endif
    </div>

    <div class="countdown-content">
        <p class="countdown-eyebrow">Coming Soon</p>
        <h2 class="countdown-title">{{ $settings['timer_title'] ?? 'New Release' }}</h2>
        @if(!empty($settings['timer_subtitle']))
        <p class="countdown-subtitle">{{ $settings['timer_subtitle'] }}</p>
        @endif

        <div class="countdown-clock" id="countdownClock">
            {{-- Days --}}
            <div class="clock-unit">
                <div class="clock-box">
                    <div class="clock-digit" id="cd-d1">0</div>
                    <div class="clock-digit" id="cd-d2">0</div>
                </div>
                <div class="clock-label">Days</div>
            </div>
            <div class="clock-sep">:</div>
            {{-- Hours --}}
            <div class="clock-unit">
                <div class="clock-box">
                    <div class="clock-digit" id="cd-h1">0</div>
                    <div class="clock-digit" id="cd-h2">0</div>
                </div>
                <div class="clock-label">Hours</div>
            </div>
            <div class="clock-sep">:</div>
            {{-- Minutes --}}
            <div class="clock-unit">
                <div class="clock-box">
                    <div class="clock-digit" id="cd-m1">0</div>
                    <div class="clock-digit" id="cd-m2">0</div>
                </div>
                <div class="clock-label">Minutes</div>
            </div>
            <div class="clock-sep">:</div>
            {{-- Seconds --}}
            <div class="clock-unit">
                <div class="clock-box">
                    <div class="clock-digit" id="cd-s1">0</div>
                    <div class="clock-digit" id="cd-s2">0</div>
                </div>
                <div class="clock-label">Seconds</div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════ 6 VERTICAL BANNERS ═══════════ --}}
<section id="banners">
    <div class="banners-row-header" data-aos="fade-up">
        <div class="banners-row-title">
            <span>Only on</span> SK Artistic Films
        </div>
    </div>

    <div class="banners-six-grid" data-aos="fade-up" data-aos-delay="80">
        @php
            $allBanners = $banners->count() ? $banners : collect();
            $emptySlots = 6 - $allBanners->count();
        @endphp

        {{-- Admin-set banners ONLY --}}
        @foreach($allBanners as $banner)
        <div class="nf-poster">
            @if($banner->image)
                <img class="nf-poster-img"
                     src="{{ $banner->image) }}"
                     alt="{{ $banner->title }}"
                     loading="lazy">
            @else
                <div class="nf-poster-placeholder">
                    <i class="fas fa-film" style="font-size:2rem;color:rgba(255,255,255,.07);"></i>
                </div>
            @endif
            <div class="nf-poster-overlay">
                @if($banner->genre)
                <div class="nf-poster-genre">{{ $banner->genre }}</div>
                @endif
                <div class="nf-poster-title">{{ $banner->title }}</div>
            </div>
        </div>
        @endforeach

        {{-- Empty placeholder slots --}}
        @for($i = 0; $i < $emptySlots; $i++)
        <div class="nf-poster">
            <div class="nf-poster-placeholder">
                <i class="fas fa-film" style="font-size:2rem;color:rgba(255,255,255,.06);"></i>
                <span style="font-size:10px;color:rgba(255,255,255,.15);letter-spacing:.1em;font-family:'Barlow',sans-serif;">COMING SOON</span>
            </div>
        </div>
        @endfor
    </div>
</section>

{{-- ═══════════ MOVIES / FILMS PORTFOLIO ═══════════ --}}
<section id="films" style="background:#0e0e0e;padding:70px 0;">
    <div style="padding:0 60px;margin-bottom:24px;display:flex;align-items:flex-end;justify-content:space-between;" data-aos="fade-up">
        <div>
            <p class="section-label">Our Portfolio</p>
            <h2 style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);color:#e5e5e5;margin:0;">
                Our Films
            </h2>
        </div>
    </div>

    {{-- Horizontal scrollable movie posters --}}
    <div style="position:relative;" data-aos="fade-up" data-aos-delay="80">

        {{-- Edge fade right --}}
        <div style="position:absolute;top:0;right:0;bottom:0;width:100px;background:linear-gradient(to left,#0e0e0e,transparent);pointer-events:none;z-index:2;"></div>

        {{-- Arrow buttons --}}
        <button class="scroll-arrow left" id="filmsLeft"
            style="position:absolute;left:16px;top:50%;transform:translateY(-50%);z-index:5;">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="scroll-arrow right" id="filmsRight"
            style="position:absolute;right:16px;top:50%;transform:translateY(-50%);z-index:5;">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div id="filmsTrack"
             style="display:flex;gap:10px;overflow-x:auto;scrollbar-width:none;padding:0 60px 12px;scroll-snap-type:x mandatory;cursor:grab;">

            @forelse($movies as $movie)
            <div onclick="openNfModal({{ $movie->id }})"
                 style="flex-shrink:0;width:200px;scroll-snap-align:start;position:relative;overflow:hidden;border-radius:4px;aspect-ratio:2/3;cursor:pointer;transition:transform .3s cubic-bezier(.4,0,.2,1),box-shadow .3s;"
                 class="film-poster-card">

                @if($movie->poster)
                    <img src="{{ $movie->poster) }}"
                         alt="{{ $movie->title }}"
                         loading="lazy"
                         style="width:100%;height:100%;object-fit:cover;display:block;transition:filter .3s;">
                @else
                    <div style="width:100%;height:100%;background:#1a1a1a;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;">
                        <i class="fas fa-film" style="font-size:2rem;color:rgba(255,255,255,.07);"></i>
                        <span style="font-size:10px;color:rgba(255,255,255,.15);letter-spacing:.1em;font-family:'Barlow',sans-serif;">NO POSTER</span>
                    </div>
                @endif

                {{-- Gradient overlay --}}
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.96) 0%,rgba(0,0,0,.4) 35%,transparent 65%);display:flex;flex-direction:column;justify-content:flex-end;padding:14px 12px;">
                    @if($movie->genre)
                    <div style="font-family:'Barlow',sans-serif;font-size:9px;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:var(--gold);margin-bottom:4px;">{{ $movie->genre }}</div>
                    @endif
                    <div style="font-family:'Barlow',sans-serif;font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:.03em;color:#fff;line-height:1.1;">{{ $movie->title }}</div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:5px;font-family:'Barlow',sans-serif;font-size:11px;color:rgba(255,255,255,.45);">
                        @if($movie->year)<span>{{ $movie->year }}</span>@endif
                        @if($movie->rating)<span><i class="fas fa-star" style="color:var(--gold);font-size:9px;"></i> {{ $movie->rating }}</span>@endif
                    </div>
                </div>

                {{-- Play btn on hover --}}
                <button onclick="event.stopPropagation();openNfModal({{ $movie->id }})"
                    style="position:absolute;top:10px;right:10px;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.85);border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;color:#000;opacity:0;transform:scale(.8);transition:opacity .25s,transform .25s;"
                    class="film-play-btn">
                    <i class="fas fa-play" style="margin-left:2px;"></i>
                </button>
            </div>
            @empty
            <div style="display:flex;align-items:center;gap:20px;padding:60px 0;color:rgba(255,255,255,.2);">
                <i class="fas fa-film" style="font-size:3rem;"></i>
                <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;">Films coming soon</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════ ABOUT ═══════════ --}}
<section id="about" class="section">
    <div class="container">
        <div class="about-grid">
            <div class="about-img-wrap" data-aos="fade-right">
                @if(!empty($settings['about_image']))
                    <img src="{{ $settings['about_image']) }}" alt="About">
                @else
                    <div style="width:100%;aspect-ratio:4/5;background:#1a1a1a;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-camera" style="font-size:4rem;color:rgba(255,255,255,.04);"></i>
                    </div>
                @endif
                <div class="about-badge">
                    @php $yrs = date('Y') - (int)($settings['founded_year'] ?? 2012); @endphp
                    <span class="num">{{ $yrs }}+</span>
                    <span class="lbl">Years of<br>Excellence</span>
                </div>
            </div>

            <div data-aos="fade-left">
                <p class="section-label">About Us</p>
                <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.8rem,3.5vw,2.8rem);">
                    {{ $settings['about_title'] ?? 'SK Artistic Films' }}
                </h2>
                <div class="divider"></div>
                <p style="font-family:'Barlow',sans-serif;font-size:15px;color:rgba(255,255,255,.55);line-height:1.85;margin-bottom:32px;">
                    {{ $settings['about_text'] ?? '' }}
                </p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div style="padding:20px;border:1px solid rgba(201,168,76,.1);background:rgba(201,168,76,.03);">
                        <i class="fas fa-film" style="color:var(--gold);font-size:1.2rem;margin-bottom:8px;display:block;"></i>
                        <p style="font-family:'Barlow',sans-serif;font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:3px;">Films</p>
                        <p style="font-family:'Bebas Neue',sans-serif;font-size:2.2rem;color:#e5e5e5;line-height:1;">{{ $movies->count() }}+</p>
                    </div>
                    <div style="padding:20px;border:1px solid rgba(201,168,76,.1);background:rgba(201,168,76,.03);">
                        <i class="fas fa-calendar-alt" style="color:var(--gold);font-size:1.2rem;margin-bottom:8px;display:block;"></i>
                        <p style="font-family:'Barlow',sans-serif;font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:3px;">Since</p>
                        <p style="font-family:'Bebas Neue',sans-serif;font-size:2.2rem;color:#e5e5e5;line-height:1;">{{ $settings['founded_year'] ?? '2012' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ CHARACTERS ═══════════ --}}
<section id="characters" class="section">
    <div class="container">
        <div class="chars-row-header" data-aos="fade-up">
            <div>
                <p class="section-label">Meet The Cast</p>
                <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);margin-bottom:0;">
                    Characters &amp; Cast
                </h2>
            </div>
        </div>

        <div class="chars-scroll-wrap" data-aos="fade-up" data-aos-delay="80" style="position:relative;">
            <button class="scroll-arrow left" id="charsLeft"><i class="fas fa-chevron-left"></i></button>
            <button class="scroll-arrow right" id="charsRight"><i class="fas fa-chevron-right"></i></button>

            <div class="chars-track" id="charsTrack">
                @forelse($characters as $char)
                <div class="char-card">
                    <div class="char-photo">
                        @if($char->photo)
                            <img src="{{ $char->photo) }}" alt="{{ $char->name }}" loading="lazy">
                        @else
                            <div class="char-placeholder">{{ strtoupper(substr($char->name,0,1)) }}</div>
                        @endif
                    </div>
                    <div class="char-info">
                        @if($char->role)<div class="char-role">{{ $char->role }}</div>@endif
                        <div class="char-name">{{ $char->name }}</div>
                        @if($char->actor_name)<div class="char-actor">{{ $char->actor_name }}</div>@endif
                    </div>
                </div>
                @empty
                <div style="padding:60px 0;color:rgba(255,255,255,.2);display:flex;align-items:center;gap:16px;">
                    <i class="fas fa-users" style="font-size:2.5rem;"></i>
                    <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;">Cast coming soon</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ DISCOVER MORE PROJECTS ═══════════ --}}
@if($movies->count() >= 2)
<section id="projects" class="section">
    <div class="container">
        <div style="margin-bottom:36px;" data-aos="fade-up">
            <p class="section-label">Explore More</p>
            <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);">
                Discover Our Projects
            </h2>
        </div>

        <div class="projects-mosaic" data-aos="fade-up" data-aos-delay="80">
            @foreach($movies->take(5) as $i => $movie)
            <div class="project-tile" onclick="openNfModal({{ $movie->id }})">
                @if($movie->banner ?? $movie->poster)
                    <img src="{{ asset('uploads/' . ($movie->banner ?? $movie->poster)) }}" alt="{{ $movie->title }}" loading="lazy">
                @else
                    <div style="width:100%;height:100%;background:#1a1a1a;min-height:180px;"></div>
                @endif
                <div class="project-tile-info">
                    @if($movie->genre)<div class="project-tile-genre">{{ $movie->genre }}</div>@endif
                    <div class="project-tile-title">{{ $movie->title }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ SOFTWARE ═══════════ --}}
<section id="software" class="section">
    <div class="container">
        <div style="text-align:center;margin-bottom:48px;" data-aos="fade-up">
            <p class="section-label">Our Toolkit</p>
            <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);">
                Software We Use
            </h2>
        </div>
        <div class="software-row" data-aos="fade-up" data-aos-delay="60">
            @php
                $sws = [
                    ['icon'=>'fas fa-video',       'name'=>'Adobe Premiere Pro',  'role'=>'Video Editing'],
                    ['icon'=>'fas fa-magic',        'name'=>'After Effects',       'role'=>'VFX & Motion'],
                    ['icon'=>'fas fa-adjust',       'name'=>'DaVinci Resolve',     'role'=>'Color Grading'],
                    ['icon'=>'fas fa-headphones',   'name'=>'Adobe Audition',      'role'=>'Sound Design'],
                    ['icon'=>'fas fa-layer-group',  'name'=>'Photoshop',           'role'=>'Graphics'],
                    ['icon'=>'fas fa-cube',         'name'=>'Cinema 4D',           'role'=>'3D & Animation'],
                ];
            @endphp
            @foreach($sws as $sw)
            <div class="sw-item">
                <div class="sw-icon"><i class="{{ $sw['icon'] }}"></i></div>
                <p class="sw-name">{{ $sw['name'] }}</p>
                <p class="sw-role">{{ $sw['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ REVIEWS ═══════════ --}}
<section id="reviews" class="section">
    <div class="container">
        <div style="text-align:center;margin-bottom:48px;" data-aos="fade-up">
            <p class="section-label">Testimonials</p>
            <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);">
                What People Say
            </h2>
        </div>
        <div class="reviews-grid">
            @php
                $revs = [
                    ['text'=>'An extraordinary team with an unmatched eye for storytelling. Every frame they produce is a masterpiece.','name'=>'Ahmad Raza','role'=>'Film Critic','stars'=>5],
                    ['text'=>'SK Artistic Films brought our vision to life with professionalism and artistry we had never experienced before.','name'=>'Sana Malik','role'=>'Producer','stars'=>5],
                    ['text'=>'Working with this team was transformative. Their passion for cinema is evident in every scene they compose.','name'=>'Omar Farooq','role'=>'Director','stars'=>5],
                ];
            @endphp
            @foreach($revs as $i => $rev)
            <div class="review-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="review-stars">
                    @for($s=0;$s<$rev['stars'];$s++)<i class="fas fa-star"></i>@endfor
                </div>
                <p class="review-text">"{{ $rev['text'] }}"</p>
                <div class="review-author">
                    <div class="review-avatar">{{ strtoupper(substr($rev['name'],0,1)) }}</div>
                    <div>
                        <p class="review-name">{{ $rev['name'] }}</p>
                        <p class="review-role">{{ $rev['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ CONTACT ═══════════ --}}
<section id="contact" class="section">
    <div class="container">
        <div style="margin-bottom:48px;" data-aos="fade-up">
            <p class="section-label">Get In Touch</p>
            <h2 class="section-title" style="font-family:'Barlow',sans-serif;font-weight:800;text-transform:uppercase;font-size:clamp(1.6rem,3vw,2.4rem);">
                Contact Us
            </h2>
            <div class="divider"></div>
        </div>
        <div class="contact-grid">
            <div data-aos="fade-right">
                @if(!empty($settings['contact_email']))
                <div class="contact-item">
                    <div class="contact-icon-box"><i class="fas fa-envelope"></i></div>
                    <div><p class="contact-item-label">Email</p><p class="contact-item-val">{{ $settings['contact_email'] }}</p></div>
                </div>
                @endif
                @if(!empty($settings['contact_phone']))
                <div class="contact-item">
                    <div class="contact-icon-box"><i class="fas fa-phone"></i></div>
                    <div><p class="contact-item-label">Phone</p><p class="contact-item-val">{{ $settings['contact_phone'] }}</p></div>
                </div>
                @endif
                @if(!empty($settings['contact_address']))
                <div class="contact-item">
                    <div class="contact-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <div><p class="contact-item-label">Location</p><p class="contact-item-val">{{ $settings['contact_address'] }}</p></div>
                </div>
                @endif
                <div style="display:flex;gap:10px;margin-top:8px;">
                    @if(!empty($settings['facebook']))<a href="{{ $settings['facebook'] }}" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>@endif
                    @if(!empty($settings['instagram']))<a href="{{ $settings['instagram'] }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>@endif
                    @if(!empty($settings['youtube']))<a href="{{ $settings['youtube'] }}" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>@endif
                </div>
            </div>
            <div data-aos="fade-left">
                <form style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <input type="text"  class="cf-field" placeholder="Your Name">
                        <input type="email" class="cf-field" placeholder="Email Address">
                    </div>
                    <input type="text"  class="cf-field" placeholder="Subject">
                    <textarea class="cf-field" rows="5" placeholder="Your Message"></textarea>
                    <button type="submit" class="btn btn-gold" style="align-self:flex-start;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ NETFLIX MODAL ═══════════ --}}
<div class="nf-modal-backdrop" id="nfModal" onclick="handleModalBackdropClick(event)">
    <div class="nf-modal" id="nfModalBox">
        <button class="nf-modal-close" id="nfModalCloseBtn">✕</button>
        <div class="nf-modal-hero">
            <div id="nfModalMedia" style="width:100%;height:100%;"></div>
            <div class="nf-modal-hero-content">
                <h2 class="nf-modal-title" id="nfModalTitle"></h2>
                <div style="display:flex;gap:10px;" id="nfModalBtns"></div>
            </div>
        </div>
        <div class="nf-modal-body">
            <div>
                <div class="nf-meta-row" id="nfModalMeta"></div>
                <p class="nf-modal-desc" id="nfModalDesc"></p>
            </div>
            <div id="nfModalDetails"></div>
        </div>
    </div>
</div>

{{-- Movie data for JS --}}
<script>
const MOVIES = {
    @foreach($movies as $movie)
    {{ $movie->id }}: {
        id:        {{ $movie->id }},
        title:     @json($movie->title),
        genre:     @json($movie->genre ?? ''),
        year:      @json($movie->year ?? ''),
        duration:  @json($movie->duration ? $movie->duration . ' min' : ''),
        rating:    @json($movie->rating ?? ''),
        desc:      @json($movie->description ?? ''),
        banner:    @json($movie->banner ? asset('uploads/' . $movie->banner) : ($movie->poster ? asset('uploads/' . $movie->poster) : '')),
        videoType: @json($movie->video_type ?? 'youtube'),
        videoYt:   @json($movie->youtube_embed ?? ''),
        videoFile: @json($movie->video_file ? asset('uploads/' . $movie->video_file) : ''),
        cast:      @json($movie->characters->pluck('name')->join(', ')),
    },
    @endforeach
};

const COUNTDOWN_DATE = @json($settings['timer_date'] ?? '');
</script>
@endsection

@section('scripts')
<script>
/* ── Countdown Timer ── */
if (COUNTDOWN_DATE) {
    const target = new Date(COUNTDOWN_DATE).getTime();

    function pad(n) { return String(n).padStart(2,'0'); }

    function setDigits(prefix, val) {
        const s = pad(val);
        const d1 = document.getElementById(prefix + '1');
        const d2 = document.getElementById(prefix + '2');
        if (d1) d1.textContent = s[0];
        if (d2) d2.textContent = s[1];
    }

    function updateClock() {
        const now  = Date.now();
        const diff = target - now;

        if (diff <= 0) {
            const clock = document.getElementById('countdownClock');
            if (clock) clock.innerHTML = '<div class="countdown-expired">🎬 Now Showing!</div>';
            return;
        }

        const days  = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins  = Math.floor((diff % 3600000)  / 60000);
        const secs  = Math.floor((diff % 60000)    / 1000);

        setDigits('cd-d', days);
        setDigits('cd-h', hours);
        setDigits('cd-m', mins);
        setDigits('cd-s', secs);
    }

    updateClock();
    setInterval(updateClock, 1000);
}

/* ── Character scroll ── */
function initDrag(trackId) {
    const el = document.getElementById(trackId);
    if (!el) return;
    let down = false, sx, sl;
    el.addEventListener('mousedown', e => { down=true; el.classList.add('grabbing'); sx=e.pageX-el.offsetLeft; sl=el.scrollLeft; });
    document.addEventListener('mouseup', () => { down=false; el.classList.remove('grabbing'); });
    document.addEventListener('mousemove', e => {
        if (!down) return;
        e.preventDefault();
        el.scrollLeft = sl - (e.pageX - el.offsetLeft - sx) * 1.5;
    });
}

initDrag('charsTrack');

document.getElementById('charsLeft')?.addEventListener('click',  () => { document.getElementById('charsTrack').scrollBy({left:-600,behavior:'smooth'}); });
document.getElementById('charsRight')?.addEventListener('click', () => { document.getElementById('charsTrack').scrollBy({left: 600,behavior:'smooth'}); });

/* ── Netflix Modal ── */
function openNfModal(id) {
    const m = MOVIES[id];
    if (!m) return;

    let media = '';
    if (m.videoType === 'youtube' && m.videoYt) {
        media = `<iframe src="${m.videoYt}?autoplay=1&mute=1" allow="autoplay;fullscreen" allowfullscreen style="position:absolute;inset:0;width:100%;height:100%;border:none;"></iframe>`;
    } else if (m.videoType === 'upload' && m.videoFile) {
        media = `<video autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;"><source src="${m.videoFile}"></video>`;
    } else if (m.banner) {
        media = `<img src="${m.banner}" style="width:100%;height:100%;object-fit:cover;" alt="${m.title}">`;
    } else {
        media = `<div style="width:100%;height:100%;background:#111;display:flex;align-items:center;justify-content:center;"><i class="fas fa-film" style="font-size:4rem;color:rgba(255,255,255,.06);"></i></div>`;
    }

    document.getElementById('nfModalMedia').innerHTML  = media;
    document.getElementById('nfModalTitle').textContent = m.title;

    let btns = '';
    if (m.videoYt || m.videoFile) {
        btns += `<button class="btn-nf-play" style="font-size:13px;padding:10px 24px;" onclick="playFull(${m.id})"><i class="fas fa-play"></i> Play</button>`;
    }
    document.getElementById('nfModalBtns').innerHTML = btns;

    let meta = '';
    if (m.year)     meta += `<span class="nf-meta-year">${m.year}</span>`;
    if (m.duration) meta += `<span class="nf-meta-dur">${m.duration}</span>`;
    if (m.rating)   meta += `<span class="nf-meta-badge">⭐ ${m.rating}</span>`;
    document.getElementById('nfModalMeta').innerHTML = meta;

    document.getElementById('nfModalDesc').textContent = m.desc || '';

    let det = '';
    if (m.cast)  det += `<p class="nf-detail-row"><b>Cast: </b>${m.cast}</p>`;
    if (m.genre) det += `<p class="nf-detail-row"><b>Genre: </b>${m.genre}</p>`;
    document.getElementById('nfModalDetails').innerHTML = det;

    document.getElementById('nfModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeNfModal() {
    document.getElementById('nfModal').classList.remove('open');
    document.getElementById('nfModalMedia').innerHTML = '';
    document.body.style.overflow = '';
}

function handleModalBackdropClick(e) {
    if (e.target === document.getElementById('nfModal')) closeNfModal();
}

document.getElementById('nfModalCloseBtn').addEventListener('click', closeNfModal);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNfModal(); });

function playFull(id) {
    const m = MOVIES[id];
    if (!m) return;
    const src  = m.videoType === 'youtube' ? m.videoYt + '?autoplay=1' : m.videoFile;
    const isYt = m.videoType === 'youtube';
    document.getElementById('nfModalMedia').innerHTML = isYt
        ? `<iframe src="${src}" allow="autoplay;fullscreen" allowfullscreen style="position:absolute;inset:0;width:100%;height:100%;border:none;"></iframe>`
        : `<video controls autoplay style="width:100%;height:100%;object-fit:cover;"><source src="${src}"></video>`;
}
</script>
@endsection