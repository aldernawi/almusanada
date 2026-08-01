<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $profile->company_name ?? 'الشركة المساندة' }} - خدمات احترافية متكاملة">
    <title>{{ $profile->company_name ?? 'الشركة المساندة' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: {{ $profile->primary_color ?? '#2563eb' }};
            --primary-dark: {{ $profile->secondary_color ?? '#1d4ed8' }};
            --dark: #080c14;
            --dark-2: #0f172a;
            --dark-3: #1e293b;
            --slate: #334155;
            --text: #1e293b;
            --muted: #64748b;
            --light: #f1f5f9;
            --border: rgba(226, 232, 240, 0.8);
            --white: #ffffff;
            --radius: 20px;
            --glow: rgba(37, 99, 235, 0.25);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; font-size: {{ $profile->font_size ?? '12px' }}; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--white);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.7;
        }

        /* ══════════════════════════════
           NAVBAR
        ══════════════════════════════ */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 0 2rem;
            transition: all 0.4s ease;
        }

        .navbar-inner {
            max-width: 1300px;
            margin: 0 auto;
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .navbar.scrolled {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.05);
        }

        .nav-logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo-img {
            height: 50px; 
            width: auto; 
            object-fit: contain;
            filter: drop-shadow(0 4px 15px rgba(0,0,0,0.2)) brightness(0) invert(1);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled .nav-logo-img {
            filter: drop-shadow(0 2px 5px rgba(30,58,138,0.1));
            height: 42px;
        }



        .nav-links {
            display: flex;
            list-style: none;
            gap: 0.25rem;
            align-items: center;
        }

        .nav-links a {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.93rem;
            transition: all 0.2s;
        }

        .nav-links a:hover { color: var(--primary); background: rgba(37,99,235,0.06); }

        .nav-btn {
            background: var(--dark) !important;
            color: white !important;
            padding: 0.6rem 1.4rem !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            transition: all 0.3s !important;
            box-shadow: 0 4px 14px rgba(8,12,20,0.2) !important;
        }

        .nav-btn:hover {
            background: var(--primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--glow) !important;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .hamburger span {
            width: 26px; height: 2.5px;
            background: var(--dark);
            border-radius: 3px;
            transition: all 0.3s;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            background: white;
            border-top: 1px solid var(--border);
            padding: 1rem 2rem 1.5rem;
            gap: 0.4rem;
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .mobile-menu a {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            transition: all 0.2s;
        }

        .mobile-menu a:hover { background: var(--light); color: var(--primary); }

        /* ══════════════════════════════
           HERO
        ══════════════════════════════ */
        .hero {
            min-height: 100vh;
            background: var(--dark);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 8rem 2rem 5rem;
        }

        /* Grid texture */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Glow orbs */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .hero-orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.3) 0%, transparent 70%);
            top: -200px; right: -150px;
        }

        .hero-orb-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.2) 0%, transparent 70%);
            bottom: -100px; left: -100px;
        }

        .hero-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(6,182,212,0.15) 0%, transparent 70%);
            top: 50%; right: 20%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50px;
            padding: 0.45rem 1.25rem;
            color: rgba(255,255,255,0.75);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .hero-badge-dot {
            width: 8px; height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px #22c55e;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.3); }
        }

        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 900;
            color: white;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 1.5rem;
        }

        .hero h1 .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #60a5fa 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shine 4s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        .hero-desc {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.55);
            max-width: 620px;
            margin: 0 auto 3rem;
            font-weight: 400;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 5rem;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 2.5rem;
            background: var(--primary);
            color: white;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 0 0 1px rgba(37,99,235,0.5), 0 8px 32px rgba(37,99,235,0.4);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 0 1px rgba(37,99,235,0.7), 0 12px 40px rgba(37,99,235,0.5);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 2.5rem;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.14);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-3px);
        }

        /* Stats row */
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 0;
            border-top: 1px solid rgba(255,255,255,0.07);
            padding-top: 3rem;
        }

        .hero-stat {
            flex: 1;
            max-width: 180px;
            text-align: center;
            padding: 0 1.5rem;
            border-left: 1px solid rgba(255,255,255,0.07);
        }

        .hero-stat:last-child { border-left: none; }

        .hero-stat-num {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
            display: block;
            letter-spacing: -1px;
        }

        .hero-stat-label {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
        }

        /* Scroll indicator  */
        .scroll-down {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.3);
            font-size: 0.75rem;
            font-weight: 600;
            animation: bounce 2.5s infinite;
        }

        .scroll-down svg { width: 20px; height: 20px; }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(8px); }
        }

        /* ══════════════════════════════
           SHARED SECTION STYLES
        ══════════════════════════════ */
        .section {
            padding: 7rem 2rem;
        }

        .section-light { background: white; }
        .section-gray { background: #f8fafc; }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .section-tag::before {
            content: '';
            display: block;
            width: 24px; height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 900;
            color: var(--dark);
            line-height: 1.2;
            letter-spacing: -0.75px;
        }

        .section-desc {
            font-size: 1.05rem;
            color: var(--muted);
            margin-top: 0.75rem;
            max-width: 540px;
            line-height: 1.85;
        }

        .section-header {
            margin-bottom: 4rem;
        }

        /* ══════════════════════════════
           SERVICES
        ══════════════════════════════ */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .service-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: default;
        }

        .service-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(37,99,235,0.03) 0%, rgba(139,92,246,0.03) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
            border-color: rgba(37,99,235,0.2);
            box-shadow: 0 24px 60px rgba(37,99,235,0.1), 0 4px 16px rgba(0,0,0,0.05);
        }

        .service-card:hover::after { opacity: 1; }

        .service-num {
            position: absolute;
            top: 2rem; left: 2rem;
            font-size: 5rem;
            font-weight: 900;
            color: rgba(37,99,235,0.05);
            line-height: 1;
            transition: color 0.3s;
        }

        .service-card:hover .service-num { color: rgba(37,99,235,0.08); }

        .service-icon-wrap {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(139,92,246,0.1));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.75rem;
            position: relative;
            z-index: 1;
            transition: all 0.3s;
        }

        .service-icon-wrap svg { width: 26px; height: 26px; color: var(--primary); transition: color 0.3s; }

        .service-card:hover .service-icon-wrap {
            background: var(--primary);
            box-shadow: 0 8px 24px var(--glow);
        }

        .service-card:hover .service-icon-wrap svg { color: white; }

        .service-card h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .service-card p {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.85;
            position: relative;
            z-index: 1;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
            grid-column: 1/-1;
        }

        /* ══════════════════════════════
           REGULATIONS
        ══════════════════════════════ */
        .regulations-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .regulation-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .regulation-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 3px;
            height: 100%;
            background: var(--primary);
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.3s;
        }

        .regulation-card:hover {
            border-color: rgba(37,99,235,0.2);
            box-shadow: 0 8px 32px rgba(37,99,235,0.08);
            transform: translateX(-4px);
        }

        .regulation-card:hover::before { transform: scaleY(1); }

        .reg-num {
            min-width: 46px; height: 46px;
            background: var(--dark);
            color: white;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .reg-body h3 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.4rem;
        }

        .reg-body p {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.75;
        }

        /* ══════════════════════════════
           CONTACT / CTA
        ══════════════════════════════ */
        .cta-wrap {
            background: var(--dark);
            border-radius: 28px;
            padding: 5rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .cta-orb {
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            filter: blur(80px);
            top: -200px; right: -150px;
            background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, transparent 70%);
        }

        .cta-orb-2 {
            width: 350px; height: 350px;
            filter: blur(80px);
            bottom: -100px; left: -100px;
            background: radial-gradient(circle, rgba(139,92,246,0.18) 0%, transparent 70%);
            top: auto; right: auto;
        }

        .cta-wrap .section-tag { color: rgba(255,255,255,0.5); }
        .cta-wrap .section-tag::before { background: rgba(255,255,255,0.3); }

        .cta-wrap .section-title { color: white; }
        .cta-wrap .section-desc { color: rgba(255,255,255,0.5); max-width: 480px; margin-left: auto; margin-right: auto; }

        .contact-cards {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.25rem;
            margin-top: 3rem;
            position: relative;
            z-index: 1;
        }

        .contact-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 1.25rem 2rem;
            border-radius: 16px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
            backdrop-filter: blur(12px);
            min-width: 230px;
        }

        .contact-card:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.22);
            transform: translateY(-5px);
        }

        .contact-card.whatsapp { background: rgba(37,211,102,0.1); border-color: rgba(37,211,102,0.2); }
        .contact-card.whatsapp:hover { background: rgba(37,211,102,0.18); }

        .cc-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .cc-icon svg { width: 24px; height: 24px; }

        .cc-text h3 { font-size: 0.9rem; font-weight: 800; margin-bottom: 0.2rem; }
        .cc-text p { font-size: 0.8rem; opacity: 0.55; }

        /* ══════════════════════════════
           FOOTER
        ══════════════════════════════ */
        .site-footer {
            background: var(--dark-2);
            border-top: 1px solid rgba(255,255,255,0.05);
            padding: 2rem;
            text-align: center;
        }

        .site-footer p {
            color: rgba(255,255,255,0.3);
            font-size: 0.88rem;
        }

        .site-footer span { color: rgba(255,255,255,0.5); }

        /* ══════════════════════════════
           SCROLL ANIMATIONS
        ══════════════════════════════ */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .regulations-layout { grid-template-columns: 1fr; }
            .hero-stats { gap: 0; flex-wrap: wrap; }
            .hero-stat { min-width: 120px; border-left: none; border-top: 1px solid rgba(255,255,255,0.07); padding: 1rem; }
            .hero-stat:first-child { border-top: none; }
        }

        @media (max-width: 640px) {
            .hero h1 { font-size: 2.5rem; }
            .cta-wrap { padding: 3rem 1.5rem; }
            .contact-cards { flex-direction: column; align-items: center; }
            .section { padding: 5rem 1.25rem; }
            .services-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <!-- ═══════ NAVBAR ═══════ -->
    <nav id="navbar" class="navbar">
        <div class="navbar-inner">
            <a href="#top" class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="الشركة المساندة للتأمين" class="nav-logo-img">
            </a>

            <ul class="nav-links">
                <li><a href="#services">خدماتنا</a></li>
                <li><a href="#regulations">اللوائح</a></li>
                <li><a href="#contact">تواصل معنا</a></li>
            </ul>

            <button class="hamburger" onclick="toggleMenu()" aria-label="القائمة">
                <span></span><span></span><span></span>
            </button>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="#services" onclick="closeMenu()">خدماتنا</a>
            <a href="#regulations" onclick="closeMenu()">اللوائح</a>
            <a href="#contact" onclick="closeMenu()">تواصل معنا</a>
        </div>
    </nav>

    <!-- ═══════ HERO ═══════ -->
    <section id="top" class="hero">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                خدمات احترافية موثوقة
            </div>

            <h1>
                {!! $profile->hero_title
                    ? nl2br(e($profile->hero_title))
                    : 'شريكك الموثوق في <br><span class="gradient-text">إدارة المعاملات</span>' !!}
            </h1>

            <p class="hero-desc">
                {{ $profile->hero_description ?? 'نقدم خدمات متكاملة واحترافية بأعلى مستوى من الجودة والكفاءة، لنكون الشريك الأمثل في رحلتك نحو النجاح.' }}
            </p>

            <div class="hero-actions">
                <a href="#contact" class="btn-hero-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    تواصل معنا الآن
                </a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat" style="border: none;">
                    <span class="hero-stat-num">+1K</span>
                    <span class="hero-stat-label">معاملة منجزة</span>
                </div>
            </div>
        </div>

        <div class="scroll-down">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </section>

    <!-- ═══════ SERVICES ═══════ -->
    <section id="services" class="section section-gray">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-tag">ما نقدمه</span>
                <h2 class="section-title">خدماتنا المتميزة</h2>
            </div>

            <div class="services-grid">
                @php $icons = [
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>',
                ]; @endphp

                @forelse($services as $i => $service)
                    <div class="service-card reveal" style="transition-delay: {{ $i * 80 }}ms">
                        <span class="service-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="service-icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $icons[$i % count($icons)] !!}
                            </svg>
                        </div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->description }}</p>
                    </div>
                @empty
                    {{-- Fallback default cards if no services added yet --}}
                    @foreach([
                        ['عنوان الخدمة الأولى', 'أضف وصف الخدمة الأولى من لوحة تحكم الشركة المساندة تحت قسم "خدماتنا"، ويمكنك إضافة أي عدد من الخدمات.', 0],
                        ['عنوان الخدمة الثانية', 'أضف وصف الخدمة الثانية من لوحة تحكم الشركة المساندة تحت قسم "خدماتنا"، ويمكنك إضافة أي عدد من الخدمات.', 1],
                        ['عنوان الخدمة الثالثة', 'أضف وصف الخدمة الثالثة من لوحة تحكم الشركة المساندة تحت قسم "خدماتنا"، ويمكنك إضافة أي عدد من الخدمات.', 2],
                    ] as [$t, $d, $i])
                    <div class="service-card reveal" style="transition-delay: {{ $i * 80 }}ms">
                        <span class="service-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="service-icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $icons[$i] !!}
                            </svg>
                        </div>
                        <h3>{{ $t }}</h3>
                        <p>{{ $d }}</p>
                    </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════ REGULATIONS ═══════ -->
    <section id="regulations" class="section section-light">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-tag">الأنظمة</span>
                <h2 class="section-title">اللوائح والأنظمة</h2>
                <p class="section-desc">نلتزم بأعلى معايير الجودة ونطبق أفضل الممارسات والأنظمة المهنية لضمان خدمة متميزة</p>
            </div>

            <div class="regulations-layout">
                @forelse($regulations as $index => $regulation)
                    <div class="regulation-card reveal" style="transition-delay: {{ $index * 60 }}ms">
                        <div class="reg-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="reg-body">
                            <h3>{{ $regulation->title }}</h3>
                            <p>{{ $regulation->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg style="width:48px;height:48px;margin:0 auto 1rem;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>لا توجد لوائح معروضة حالياً، يمكن إضافتها من لوحة التحكم.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════ CONTACT CTA ═══════ -->
    <section id="contact" class="section section-gray">
        <div class="container">
            <div class="cta-wrap reveal">
                <div class="cta-orb"></div>
                <div class="cta-orb cta-orb-2"></div>
                <div style="position: relative; z-index: 1;">
                    <span class="section-tag">تواصل معنا</span>
                    <h2 class="section-title" style="color: white; margin-top: 0.75rem;">نحن هنا لمساعدتك</h2>
                    <p class="section-desc">لا تتردد في التواصل معنا، فريقنا المتخصص جاهز للإجابة على جميع استفساراتك في أسرع وقت</p>

                    <div class="contact-cards">
                        @if($profile->contact_email)
                            <a href="mailto:{{ $profile->contact_email }}" class="contact-card">
                                <div class="cc-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="cc-text">
                                    <h3>البريد الإلكتروني</h3>
                                    <p>{{ $profile->contact_email }}</p>
                                </div>
                            </a>
                        @endif

                        @if($profile->whatsapp_number)
                            <a href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" class="contact-card whatsapp">
                                <div class="cc-icon" style="background: rgba(37,211,102,0.15);">
                                    <svg fill="currentColor" viewBox="0 0 24 24" style="color: #25D366;">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </div>
                                <div class="cc-text">
                                    <h3>واتساب</h3>
                                    <p>تواصل معنا مباشرة</p>
                                </div>
                            </a>
                        @endif

                        @if(!$profile->contact_email && !$profile->whatsapp_number)
                            <a href="{{ route('customer.login') }}" class="contact-card">
                                <div class="cc-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="cc-text">
                                    <h3>بوابة الاستعلام</h3>
                                    <p>تتبع معاملاتك بسهولة</p>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════ FOOTER ═══════ -->
    <footer class="site-footer">
        <p>{{ $profile->footer_text ?? '© ' . date('Y') . ' جميع الحقوق محفوظة' }} &nbsp;—&nbsp; <span>{{ $profile->company_name ?? 'الشركة المساندة' }}</span></p>
    </footer>

    <script>
        // ── Navbar scroll effect ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // ── Mobile menu ──
        function toggleMenu() {
            const m = document.getElementById('mobileMenu');
            m.style.display = m.style.display === 'flex' ? 'none' : 'flex';
        }
        function closeMenu() {
            document.getElementById('mobileMenu').style.display = 'none';
        }

        // ── Smooth scroll ──
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const t = document.querySelector(a.getAttribute('href'));
                if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
            });
        });

        // ── Reveal on scroll ──
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(el => {
                if (el.isIntersecting) {
                    el.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>