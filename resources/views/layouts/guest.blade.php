<!DOCTYPE html>
<html lang="ar" dir="rtl" data-lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>شركة المساندة للتأمين</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --navy:#1D3A63;
    --navy-deep:#15294A;
    --teal:#1C6B5E;
    --gold:#B98A2E;
    --gold-light:#D7B369;
    --cream:#F7F5F0;
    --card:#EFEBE1;
    --ink:#20241F;
    --ink-soft:#4B5148;
    --line:#D8D2C2;
    --radius:2px;
    /* Arabic defaults */
    --f-body:'Cairo', sans-serif;
    --f-head:'Cairo', sans-serif;
    --f-mono:'IBM Plex Mono', monospace;
    --head-weight:700;
    --head-style:normal;
    --head-spacing:0;
    --h1-size:clamp(34px,4vw,52px);
    --h1-line:1.28;
    --body-line:1.6;
    --eyebrow-spacing:0.06em;
    --eyebrow-transform:none;
  }
  [data-lang="en"]{
    --f-body:'IBM Plex Sans', sans-serif;
    --f-head:'Fraunces', serif;
    --f-mono:'IBM Plex Mono', monospace;
    --head-weight:600;
    --head-style:normal;
    --head-spacing:-0.01em;
    --h1-size:clamp(38px,4.4vw,58px);
    --h1-line:1.06;
    --body-line:1.5;
    --eyebrow-spacing:0.14em;
    --eyebrow-transform:uppercase;
  }

  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}
  body{
    background:var(--cream);
    color:var(--ink);
    font-family:var(--f-body);
    line-height:var(--body-line);
    -webkit-font-smoothing:antialiased;
    transition:font-family .1s ease;
  }
  h1,h2,h3{
    font-family:var(--f-head);
    font-weight:var(--head-weight);
    font-style:var(--head-style);
    color:var(--navy-deep);
    letter-spacing:var(--head-spacing);
  }
  a{color:inherit; text-decoration:none;}
  .wrap{max-width:1180px; margin:0 auto; padding:0 32px;}

  /* ===== Lang toggle ===== */
  [data-lang="ar"] .en{display:none!important;}
  [data-lang="en"] .ar{display:none!important;}
  .en,.ar{}

  .eyebrow{
    font-family:var(--f-mono);
    font-size:12px;
    letter-spacing:var(--eyebrow-spacing);
    text-transform:var(--eyebrow-transform);
    color:var(--teal);
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:500;
  }
  [dir="rtl"] .eyebrow::after{
    content:""; width:18px; height:1px; background:var(--gold); display:inline-block;
  }
  [dir="ltr"] .eyebrow::before{
    content:""; width:18px; height:1px; background:var(--gold); display:inline-block;
  }

  /* ===== Header ===== */
  header{
    position:sticky; top:0; z-index:50;
    background:var(--navy-deep);
    border-bottom:1px solid rgba(255,255,255,0.1);
  }
  .nav{
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 0;
  }
  .logo{
    font-family:var(--f-head);
    font-weight:700; font-size:19px; color:var(--cream);
    display:flex; align-items:center; gap:12px; line-height:1.2;
  }
  .logo .mark{
    width:34px; height:34px;
    border:1px solid rgba(247,245,240,0.35);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
  }
  .logo .mark .diamond{width:13px; height:13px; background:var(--cream); transform:rotate(45deg);}
  .logo .sub-line{
    display:block; font-size:12px; font-weight:500; color:#B9C6D3; letter-spacing:0.03em;
    font-family:var(--f-mono);
  }
  [data-lang="en"] .logo .sub-line{font-size:12.5px; font-family:'Cairo',sans-serif; font-weight:600; letter-spacing:0;}
  nav.links{display:flex; gap:36px; font-size:14.5px; font-weight:500; color:#B9C6D3;}
  nav.links a:hover{color:var(--cream);}
  .nav-cta{display:flex; align-items:center; gap:20px;}
  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    padding:12px 24px; font-size:14px; font-weight:600;
    font-family:var(--f-body);
    border-radius:var(--radius); cursor:pointer;
    border:1px solid transparent; transition:all .15s ease; white-space:nowrap;
  }
  .btn-primary{background:var(--navy-deep); color:var(--cream);}
  .btn-primary:hover{background:var(--navy);}
  .btn-gold{background:var(--gold); color:var(--navy-deep);}
  .btn-gold:hover{background:var(--gold-light);}
  .btn-ghost{border-color:var(--navy-deep); color:var(--navy-deep); background:transparent;}
  .btn-ghost:hover{background:var(--navy-deep); color:var(--cream);}
  .btn-ghost-light{border-color:rgba(247,245,240,0.4); color:var(--cream); background:transparent;}
  .btn-ghost-light:hover{background:var(--cream); color:var(--navy-deep); border-color:var(--cream);}
  .lang-switch{
    font-family:var(--f-mono); font-size:12px; color:#B9C6D3;
    border:1px solid rgba(247,245,240,0.3); padding:6px 12px;
    cursor:pointer; background:transparent; border-radius:var(--radius); transition:all .15s ease;
  }
  .lang-switch:hover{color:var(--cream); border-color:var(--cream);}

  /* ===== Mobile ===== */
  .menu-toggle{
    display:none; background:none; border:none; cursor:pointer;
    width:28px; height:20px; position:relative; flex-direction:column; justify-content:space-between;
  }
  .menu-toggle span{display:block; width:100%; height:2px; background:var(--cream); border-radius:1px; transition:all .3s ease;}
  .mobile-menu{
    display:none; position:fixed; top:0; left:0; right:0; bottom:0;
    background:var(--navy-deep); z-index:100;
    flex-direction:column; align-items:center; justify-content:center; gap:32px;
  }
  .mobile-menu.open{display:flex;}
  .mobile-menu a{font-size:22px; font-weight:600; color:var(--cream); opacity:0.9; transition:opacity .2s; font-family:var(--f-body);}
  .mobile-menu a:hover{opacity:1;}
  .mobile-menu .close-btn{
    position:absolute; top:24px; inset-inline-end:24px;
    background:none; border:none; color:var(--cream); font-size:28px; cursor:pointer;
  }

  /* ===== Hero ===== */
  .hero{padding:88px 0 96px; position:relative; overflow:hidden;}
  .hero-grid{display:grid; grid-template-columns:1.1fr 0.9fr; gap:60px; align-items:center;}
  .hero h1{font-size:var(--h1-size); line-height:var(--h1-line); margin:20px 0 22px;}
  .hero h1 em{font-style:italic; color:var(--teal);}
  [data-lang="ar"] .hero h1 em{font-style:normal;}
  .hero p.lede{font-size:17px; color:var(--ink-soft); max-width:480px; margin-bottom:32px;}
  .hero-actions{display:flex; gap:14px; margin-bottom:44px; flex-wrap:wrap;}
  .hero-stats{display:flex; gap:0; border-top:1px solid var(--line); padding-top:22px; flex-wrap:wrap;}
  .hero-stats > div{
    padding-inline-start:32px; margin-inline-start:32px;
    border-inline-start:1px solid var(--line);
  }
  .hero-stats > div:last-child{border-inline-start:none; padding-inline-start:0; margin-inline-start:0;}
  .hero-stats .num{font-family:var(--f-head); font-size:28px; font-weight:600; color:var(--navy-deep); display:block;}
  .hero-stats .label{font-size:12.5px; color:var(--ink-soft);}

  .coverage-card{
    background:var(--navy-deep); border-radius:4px; padding:34px; color:var(--cream);
    box-shadow:0 24px 60px -20px rgba(11,46,79,0.45);
  }
  .coverage-card .tag{font-family:var(--f-mono); font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:var(--gold-light);}
  [data-lang="ar"] .coverage-card .tag{letter-spacing:0.03em; text-transform:none;}
  .coverage-card h3{color:var(--cream); font-size:22px; margin:6px 0 4px;}
  .coverage-card .sub{font-size:13px; color:#B9C6D3;}
  .coverage-list{list-style:none; margin-top:20px;}
  .coverage-list li{padding:13px 0; border-top:1px solid rgba(255,255,255,0.1); font-size:14px;}
  .coverage-list li:first-child{border-top:none;}
  .coverage-list .dot{width:7px; height:7px; border-radius:50%; background:var(--teal); display:inline-block; margin-inline-end:10px;}
  .coverage-list .pct{font-family:var(--f-mono); color:var(--gold-light); font-size:13px;}
  .bar-bg{height:4px; background:rgba(255,255,255,0.12); border-radius:2px; margin-top:8px; overflow:hidden;}
  .bar-fill{height:100%; background:var(--teal); border-radius:2px; transition:width 1.2s ease;}

  /* ===== Statement ===== */
  .statement{background:var(--cream); text-align:center; padding:64px 0;}
  .statement .wrap{max-width:780px;}
  .statement blockquote{
    font-family:var(--f-head);
    font-weight:500; font-size:clamp(20px,2.4vw,27px); line-height:1.7;
    color:var(--navy-deep);
  }
  [data-lang="en"] .statement blockquote{font-style:italic; line-height:1.5;}
  .statement blockquote span{color:var(--teal); font-style:normal;}

  /* ===== Belief diamond ===== */
  .belief-wrap{display:flex; justify-content:center; margin:36px 0 8px;}
  .belief-mark{perspective:900px; width:150px; height:150px; display:flex; align-items:center; justify-content:center;}
  .belief-diamond{
    width:104px; height:104px; background:var(--navy);
    border:1px solid rgba(247,245,240,0.25); transform:rotate(45deg);
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 20px 40px -18px rgba(0,0,0,0.5);
  }
  .belief-word{
    transform:rotate(-45deg); font-family:var(--f-mono); font-size:12.5px;
    font-weight:500; letter-spacing:0.05em; text-transform:uppercase;
    color:var(--gold-light); text-align:center; max-width:78px; line-height:1.35;
    display:inline-block; backface-visibility:hidden;
  }
  [data-lang="ar"] .belief-word{font-family:'Cairo',sans-serif; font-size:13px; font-weight:700; text-transform:none; letter-spacing:0;}
  .belief-word.flip{animation:flipWord .7s ease;}
  @keyframes flipWord{
    0%{transform:rotate(-45deg) rotateX(0deg); opacity:1;}
    45%{transform:rotate(-45deg) rotateX(90deg); opacity:0;}
    55%{transform:rotate(-45deg) rotateX(-90deg); opacity:0;}
    100%{transform:rotate(-45deg) rotateX(0deg); opacity:1;}
  }

  /* ===== Section ===== */
  section{padding:88px 0;}
  .section-head{display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:48px; gap:40px;}
  .section-head h2{font-size:clamp(26px,2.8vw,36px); margin-top:14px;}
  .section-head p{color:var(--ink-soft); max-width:360px; font-size:15px;}

  /* ===== Services ===== */
  .services{background:var(--card); border-top:1px solid var(--line); border-bottom:1px solid var(--line);}
  .service-grid{display:grid; grid-template-columns:1fr 1fr; gap:24px;}
  .service-card{
    background:var(--cream); border:1px solid var(--line); padding:36px 32px;
    display:flex; flex-direction:column; transition:transform .18s ease, box-shadow .18s ease;
  }
  .service-card:hover{transform:translateY(-3px); box-shadow:0 12px 28px -16px rgba(11,46,79,0.2);}
  .service-mark{
    width:44px; height:44px; border:1px solid var(--line);
    display:flex; align-items:center; justify-content:center;
    font-family:var(--f-mono); font-size:13px; color:var(--gold); background:var(--cream);
    margin-bottom:22px;
  }
  .service-card h3{font-size:21px; margin-bottom:12px;}
  .service-card p.desc{font-size:14px; color:var(--ink-soft); margin-bottom:22px;}
  .service-card ul{list-style:none; margin-top:auto;}
  .service-card ul li{font-size:13.5px; padding:11px 0; border-top:1px solid var(--line); display:flex; align-items:center; gap:8px;}
  .service-card ul li:first-child{border-top:none;}
  .partner-strip{display:flex; gap:10px; flex-wrap:wrap; margin-top:6px; margin-bottom:20px;}
  .partner-chip{
    font-family:var(--f-mono); font-size:11.5px; letter-spacing:0.03em;
    padding:6px 12px; border:1px solid var(--line); color:var(--ink-soft);
  }
  [data-lang="ar"] .partner-chip{font-size:11px; letter-spacing:0;}

  /* ===== Process ===== */
  .process-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:0; border-top:1px solid var(--line);}
  .step{padding:32px 26px 0; border-inline-start:1px solid var(--line); position:relative; transition:background .2s ease;}
  .step:first-child{border-inline-start:none;}
  .step:hover{background:rgba(29,58,99,0.03);}
  .step .step-num{font-family:var(--f-mono); font-size:13px; color:var(--gold); margin-bottom:14px; display:block;}
  .step h3{font-size:17px; margin-bottom:10px;}
  .step p{font-size:13.5px; color:var(--ink-soft);}

  /* ===== Values ===== */
  .values{background:var(--navy-deep); color:var(--cream);}
  .values-grid{display:grid; grid-template-columns:repeat(5,1fr); gap:0; border-top:1px solid rgba(255,255,255,0.12);}
  .value{padding:30px 22px 4px; border-inline-start:1px solid rgba(255,255,255,0.12); transition:background .2s ease;}
  .value:first-child{border-inline-start:none;}
  .value:hover{background:rgba(255,255,255,0.04);}
  .value .value-num{font-family:var(--f-mono); font-size:12px; color:var(--gold-light); margin-bottom:14px; display:block;}
  .value h3{color:var(--cream); font-size:18px; margin-bottom:8px;}
  .value p{font-size:13px; color:#B9C6D3; line-height:1.5;}

  /* ===== Band ===== */
  .band{background:var(--navy-deep); color:var(--cream);}
  .band .wrap{display:grid; grid-template-columns:repeat(4,1fr); gap:40px;}
  .band .stat .num{font-family:var(--f-head); font-size:40px; font-weight:600; color:var(--gold-light); display:block; margin-bottom:6px;}
  .band .stat .label{font-size:13.5px; color:#B9C6D3;}

  /* ===== Testimonial ===== */
  .testimonial{max-width:720px; margin:0 auto; text-align:center;}
  .testimonial blockquote{
    font-family:var(--f-head); font-size:26px; font-weight:500;
    line-height:1.5; color:var(--navy-deep); margin-bottom:26px;
  }
  [data-lang="en"] .testimonial blockquote{font-style:italic;}
  .testimonial .who{font-size:14px; color:var(--ink-soft); font-weight:500;}
  .testimonial .who span{color:var(--teal);}

  /* ===== CTA ===== */
  .cta-band{background:var(--card); border-top:1px solid var(--line); text-align:center;}
  .cta-band h2{font-size:clamp(24px,2.8vw,36px); margin-bottom:16px;}
  .cta-band p{color:var(--ink-soft); margin-bottom:32px; font-size:15.5px; max-width:600px; margin-inline:auto;}
  .cta-actions{display:flex; gap:14px; justify-content:center; flex-wrap:wrap;}

  /* ===== Footer ===== */
  footer{background:var(--navy-deep); color:#B9C6D3; padding:64px 0 28px;}
  .footer-grid{
    display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr;
    gap:40px; padding-bottom:44px; border-bottom:1px solid rgba(255,255,255,0.1);
  }
  .footer-grid h4{
    font-family:var(--f-mono); font-size:12px; letter-spacing:0.08em;
    text-transform:uppercase; color:var(--gold-light); margin-bottom:16px;
  }
  .footer-grid ul{list-style:none;}
  .footer-grid li{margin-bottom:10px; font-size:14px;}
  .footer-grid a:hover{color:var(--cream);}
  .footer-logo{
    font-family:var(--f-head); font-weight:700; color:var(--cream); font-size:19px;
    margin-bottom:14px; display:flex; align-items:center; gap:12px;
  }
  .footer-logo .mark{
    width:30px; height:30px; border:1px solid rgba(247,245,240,0.35);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
  }
  .footer-logo .mark .diamond{width:11px; height:11px; background:var(--cream); transform:rotate(45deg);}
  .footer-logo .sub-line{
    display:block; font-size:12px; color:#B9C6D3; margin-top:3px;
    font-family:'Cairo',sans-serif; font-weight:600;
  }
  .footer-bottom{padding-top:24px; display:flex; justify-content:space-between; font-size:13px; color:#7C8FA3; flex-wrap:wrap; gap:12px;}

  /* ===== Animations ===== */
  .fade-in{opacity:0; transform:translateY(24px); transition:opacity .6s ease, transform .6s ease;}
  .fade-in.visible{opacity:1; transform:translateY(0);}

  .back-top{
    position:fixed; bottom:28px; inset-inline-start:28px; z-index:60;
    width:44px; height:44px; border-radius:50%;
    background:var(--navy-deep); color:var(--cream);
    border:none; cursor:pointer; font-size:18px;
    display:flex; align-items:center; justify-content:center;
    opacity:0; pointer-events:none; transition:opacity .3s ease, transform .3s ease;
    box-shadow:0 4px 16px rgba(0,0,0,0.3);
  }
  .back-top.show{opacity:1; pointer-events:auto;}
  .back-top:hover{transform:scale(1.1);}

  /* ===== Responsive ===== */
  @media (max-width:900px){
    .hero-grid{grid-template-columns:1fr;}
    .service-grid{grid-template-columns:1fr;}
    .process-grid{grid-template-columns:1fr 1fr;}
    .step{border-inline-start:none; border-bottom:1px solid var(--line); padding-bottom:24px; margin-bottom:24px;}
    .band .wrap{grid-template-columns:1fr 1fr; gap:28px;}
    .values-grid{grid-template-columns:1fr 1fr;}
    .value{border-inline-start:none; border-bottom:1px solid rgba(255,255,255,0.12); padding-bottom:20px; margin-bottom:16px;}
    .value:first-child{border-inline-start:none;}
    .footer-grid{grid-template-columns:1fr 1fr;}
    nav.links{display:none;}
    .menu-toggle{display:flex;}
    .nav-cta .btn-gold,.nav-cta .btn-ghost-light{display:none;}
  }
  @media (max-width:600px){
    .hero-stats > div{padding-inline-start:0!important; margin-inline-start:0!important; border-inline-start:none!important; width:100%; margin-bottom:12px;}
    .band .wrap{grid-template-columns:1fr;}
    .footer-grid{grid-template-columns:1fr;}
    .values-grid{grid-template-columns:1fr;}
    .process-grid{grid-template-columns:1fr;}
  }
</style>
</head>
<body>

<div class="mobile-menu" id="mobileMenu">
  <button class="close-btn" onclick="closeMobile()" aria-label="Close">✕</button>
  <a href="#services" onclick="closeMobile()"><span class="ar">خدماتنا</span><span class="en">Services</span></a>
  <a href="#network" onclick="closeMobile()"><span class="ar">الشبكة</span><span class="en">Network</span></a>
  <a href="#process" onclick="closeMobile()"><span class="ar">المطالبات</span><span class="en">Claims</span></a>
  <a href="#contact" onclick="closeMobile()"><span class="ar">تواصل معنا</span><span class="en">Contact</span></a>
</div>

<header>
  <div class="wrap nav">
    <div class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="Almusanada Insurance" style="height:42px;width:auto;object-fit:contain;">
      <span>
        <span class="ar">شركة المساندة للتأمين</span>
        <span class="en">Almusanada <span style="font-weight:400;color:#B9C6D3;font-size:13px;">Insurance</span></span>
        <span class="sub-line">ALMUSANADA INSURANCE</span>
      </span>
    </div>
    <nav class="links">
      <a href="#services"><span class="ar">خدماتنا</span><span class="en">Services</span></a>
      <a href="#network"><span class="ar">الشبكة</span><span class="en">Network</span></a>
      <a href="#process"><span class="ar">المطالبات</span><span class="en">Claims</span></a>
      <a href="#contact"><span class="ar">تواصل معنا</span><span class="en">Contact</span></a>
    </nav>
    <div class="nav-cta">
      <button class="lang-switch" id="langSwitch" onclick="toggleLang()">
        <span class="ar">English</span><span class="en">العربية</span>
      </button>
      <a href="#contact" class="btn btn-ghost-light"><span class="ar">تحدث معنا</span><span class="en">Talk to us</span></a>
      <a href="#services" class="btn btn-gold"><span class="ar">تواصل للشراكة</span><span class="en">Partner with us</span></a>
      <button class="menu-toggle" onclick="openMobile()" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>

<!-- ==================== HERO ==================== -->
<section class="hero">
  <div class="wrap hero-grid">
    <div class="fade-in">
      <div class="eyebrow">
        <span class="ar">الرقابة على المطالبات الطبية، منذ 2019</span>
        <span class="en">Medical Claims Oversight, Since 2019</span>
      </div>
      <h1>
        <span class="ar">رقابة توقف <em>استنزاف ميزانيتك.</em></span>
        <span class="en">Oversight that stops<br>the drain on <em>your budget.</em></span>
      </h1>
      <p class="lede">
        <span class="ar">كل مطالبة تُراجع، وكل مزود يُدقَّق. نعمل إلى جانب شركات التأمين والشركات عبر أكثر من 280 مزود رعاية، نكشف التحايل مبكرًا، ونُبقي الميزانية بمنأى عن الاستنزاف الصامت.</span>
        <span class="en">Every claim is checked, every provider is audited. We work alongside insurance partners and companies across 280+ healthcare providers, catching fraud early and keeping budgets free of silent drain.</span>
      </p>
      <div class="hero-actions">
        <a href="#services" class="btn btn-primary"><span class="ar">تواصل للشراكة</span><span class="en">Partner with us</span></a>
        <a href="#network" class="btn btn-ghost"><span class="ar">عرض شبكة مزودي الرعاية</span><span class="en">View provider network</span></a>
      </div>
      <div class="hero-stats">
        <div>
          <span class="num">280+</span>
          <span class="label"><span class="ar">مزود رعاية تحت الرقابة</span><span class="en">Providers under oversight</span></span>
        </div>
        <div>
          <span class="num"><span class="ar">شهري</span><span class="en">Monthly</span></span>
          <span class="label"><span class="ar">تقرير مطالبات مدقَّق</span><span class="en">Audited claims reporting</span></span>
        </div>
        <div>
          <span class="num">60K+</span>
          <span class="label"><span class="ar">مستفيد تحت الرقابة</span><span class="en">Beneficiaries under oversight</span></span>
        </div>
      </div>
    </div>

    <div class="coverage-card fade-in">
      <div class="tag"><span class="ar">المطالبات التي نعالجها · توزيع نموذجي</span><span class="en">Claims we process · typical split</span></div>
      <h3><span class="ar">أين تذهب المطالبات</span><span class="en">Where claims land</span></h3>
      <div class="sub"><span class="ar">توزيع نموذجي عبر شبكة مزودينا</span><span class="en">A typical distribution across our provider network</span></div>
      <ul class="coverage-list">
        <li>
          <div style="width:100%">
            <div style="display:flex;justify-content:space-between">
              <span><span class="dot"></span><span class="ar">رعاية داخل المستشفى والجراحة</span><span class="en">Inpatient &amp; surgery</span></span>
              <span class="pct">62%</span>
            </div>
            <div class="bar-bg"><div class="bar-fill" data-width="62" style="width:0%"></div></div>
          </div>
        </li>
        <li>
          <div style="width:100%">
            <div style="display:flex;justify-content:space-between">
              <span><span class="dot"></span><span class="ar">العيادات الخارجية والاستشارات</span><span class="en">Outpatient &amp; consults</span></span>
              <span class="pct">21%</span>
            </div>
            <div class="bar-bg"><div class="bar-fill" data-width="21" style="width:0%"></div></div>
          </div>
        </li>
        <li>
          <div style="width:100%">
            <div style="display:flex;justify-content:space-between">
              <span><span class="dot"></span><span class="ar">الأدوية والتشخيص</span><span class="en">Medication &amp; diagnostics</span></span>
              <span class="pct">12%</span>
            </div>
            <div class="bar-bg"><div class="bar-fill" data-width="12" style="width:0%"></div></div>
          </div>
        </li>
        <li>
          <div style="width:100%">
            <div style="display:flex;justify-content:space-between">
              <span><span class="dot"></span><span class="ar">الولادة وطب الأسنان</span><span class="en">Maternity &amp; dental</span></span>
              <span class="pct">5%</span>
            </div>
            <div class="bar-bg"><div class="bar-fill" data-width="5" style="width:0%"></div></div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- ==================== STATEMENT ==================== -->
<section class="statement">
  <div class="wrap fade-in">
    <blockquote>
      <span class="ar">المساندة ليست مجرد شركة تأمين — إنها <span>الجهة الرقابية</span> التي تقف خلف كل مطالبة، وترصد ما لا تكشفه وثيقة التأمين وحدها.</span>
      <span class="en">Almusanada isn't just an insurance company — it's the <span>audit ally</span> standing behind every claim, catching what a policy alone would miss.</span>
    </blockquote>
  </div>
</section>

<!-- ==================== VALUES ==================== -->
<section class="values">
  <div class="wrap">
    <div style="padding-top:44px" class="fade-in">
      <div class="eyebrow" style="color:var(--gold-light)">
        <span class="ar">ما نعمل بموجبه</span><span class="en">What we operate on</span>
      </div>
      <h2 style="color:var(--cream);font-size:clamp(22px,2.4vw,32px);margin-top:14px">
        <span class="ar">خمس ركائز، نلتزم بها يوميًا</span><span class="en">Five words, held to daily</span>
      </h2>
    </div>
    <div class="belief-wrap">
      <div class="belief-mark">
        <div class="belief-diamond">
          <span class="belief-word" id="beliefWord">الثقة</span>
        </div>
      </div>
    </div>
    <div class="values-grid fade-in" style="margin-top:12px">
      <div class="value">
        <span class="value-num">01</span>
        <h3><span class="ar">الثقة</span><span class="en">Trust</span></h3>
        <p><span class="ar">علاقتنا بالعضو أو الشريك تتجاوز أي مطالبة واحدة.</span><span class="en">The relationship a member or partner has with us outlasts any single claim.</span></p>
      </div>
      <div class="value">
        <span class="value-num">02</span>
        <h3><span class="ar">النزاهة</span><span class="en">Integrity</span></h3>
        <p><span class="ar">شروط التغطية تعني ما تقوله، عند التوقيع وعند المطالبة.</span><span class="en">Coverage terms mean what they say — at signing and at claim time.</span></p>
      </div>
      <div class="value">
        <span class="value-num">03</span>
        <h3><span class="ar">الكفاءة</span><span class="en">Efficiency</span></h3>
        <p><span class="ar">التسديد المباشر والإدارة المبسطة تزيل العوائق لا الجودة.</span><span class="en">Direct billing and streamlined administration remove friction, not corners.</span></p>
      </div>
      <div class="value">
        <span class="value-num">04</span>
        <h3><span class="ar">المصداقية</span><span class="en">Credibility</span></h3>
        <p><span class="ar">7 أعوام وأكثر من 280 مزود رعاية متعاقد، مبنية على الثبات لا الوعود.</span><span class="en">7 years and 280+ contracted providers built on consistency, not promises.</span></p>
      </div>
      <div class="value">
        <span class="value-num">05</span>
        <h3><span class="ar">التطوير الذكي</span><span class="en">Smart Development</span></h3>
        <p><span class="ar">تحليل مدعوم بالذكاء الاصطناعي وإدارة أطراف ثالثة تتحسن مع كل مطالبة.</span><span class="en">AI-assisted analysis and TPA operations that improve with every claim processed.</span></p>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SERVICES ==================== -->
<section class="services" id="services">
  <div class="wrap">
    <div class="section-head fade-in">
      <div>
        <div class="eyebrow"><span class="ar">دورنا الأساسي</span><span class="en">Our core role</span></div>
        <h2><span class="ar">رقابة تحمي ميزانيتك، لا تديرها فقط</span><span class="en">Oversight that protects your budget, not just manages it</span></h2>
      </div>
      <p><span class="ar">لسنا شركة تأمين تقليدية — نحن الجهة الرقابية التي تقف بين المزوّد والدافع، نراجع كل مطالبة مقابل التسعيرة الصحيحة ونوقف الاستنزاف قبل أن يقع.</span><span class="en">We're not a traditional insurer — we're the oversight layer between provider and payer, verifying every claim against the correct tariff and catching drain before it happens.</span></p>
    </div>
    <div class="service-grid">
      <div class="service-card fade-in">
        <div class="service-mark"><span class="ar">رقابة</span><span class="en">Oversight</span></div>
        <h3><span class="ar">الرقابة وكشف التحايل</span><span class="en">Oversight &amp; Fraud Detection</span></h3>
        <p class="desc"><span class="ar">كل مطالبة تمر عبر رقابة مدعومة بالذكاء الاصطناعي ترصد التحايل والإنفاق غير المبرر قبل الصرف، وتوقف الاستنزاف الناتج عنه بين المزوّد والدافع.</span><span class="en">Every claim runs through AI-assisted oversight that flags fraud and unwarranted spend before payout — closing the drain that comes from missing controls and reconciliation between provider and payer.</span></p>
        <ul>
          <li><span class="checkmark">✓</span> <span class="ar">كشف تلقائي للتحايل والاستنزاف</span><span class="en">Automated fraud &amp; drain detection</span></li>
          <li><span class="checkmark">✓</span> <span class="ar">إيقاف الصرف غير المبرر قبل وقوعه، لا بعده</span><span class="en">Spend halted before payout, not after</span></li>
          <li><span class="checkmark">✓</span> <span class="ar">تقارير رقابية دورية للشركاء</span><span class="en">Regular oversight reporting for partners</span></li>
        </ul>
      </div>
      <div class="service-card fade-in">
        <div class="service-mark">TPA</div>
        <h3><span class="ar">ضبط وربط عبر الشبكة</span><span class="en">Control &amp; Reconciliation Across the Network</span></h3>
        <p class="desc"><span class="ar">ندير معالجة المطالبات وشبكات مقدمي الرعاية نيابةً عن مصارف شريكة وشركات — ونراجع كل فاتورة مقابل قاعدة بيانات أسعار محدّثة عبر أكثر من 280 مزود متعاقد: فلا صرف دون ضبط، ولا ضبط دون ربط.</span><span class="en">We administer claims processing and provider networks on behalf of insurance partners and companies, checking every invoice against a live price-list database across 280+ contracted providers — no payout without control, no control without reconciliation.</span></p>
        <div class="partner-strip">
          <span class="partner-chip"><span class="ar">خطط شركات ذاتية التأمين</span><span class="en">Corporate self-insured plans</span></span>
          <span class="partner-chip"><span class="ar">شركاء تأمين إقليميون</span><span class="en">Regional insurance partners</span></span>
          <span class="partner-chip"><span class="ar">برامج تأمين جماعي</span><span class="en">Group health schemes</span></span>
        </div>
        <ul>
          <li><span class="checkmark">✓</span> <span class="ar">مراجعة كل مطالبة مقابل التسعيرة الصحيحة</span><span class="en">Every claim checked against the correct tariff</span></li>
          <li><span class="checkmark">✓</span> <span class="ar">قاعدة بيانات أسعار لأكثر من 280 مزود</span><span class="en">280+ provider price-list database</span></li>
          <li><span class="checkmark">✓</span> <span class="ar">تقارير مخصصة للشركاء</span><span class="en">Dedicated partner reporting</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ==================== PROCESS ==================== -->
<section id="process">
  <div class="wrap">
    <div class="section-head fade-in">
      <div>
        <div class="eyebrow"><span class="ar">كيف تعمل المطالبات</span><span class="en">How claims work</span></div>
        <h2><span class="ar">مسار تدقيق هادئ، من الزيارة إلى الدفع</span><span class="en">A quiet audit trail, from visit to payment</span></h2>
      </div>
    </div>
    <div class="process-grid fade-in">
      <div class="step">
        <span class="step-num">01</span>
        <h3><span class="ar">تُقدَّم المطالبة</span><span class="en">The claim is submitted</span></h3>
        <p><span class="ar">يفوتر المزوّد عبر شبكتنا، وفق التسعيرة المتفق عليها مسبقًا لتلك الزيارة أو الإجراء.</span><span class="en">The provider bills through our network, against the tariff agreed in advance for that visit or procedure.</span></p>
      </div>
      <div class="step">
        <span class="step-num">02</span>
        <h3><span class="ar">تُراجَع كل تفصيلة</span><span class="en">Every line is reviewed</span></h3>
        <p><span class="ar">تُوازَن كل مطالبة مقابل التسعيرة الصحيحة، وتُفحص بهدوء بحثًا عن أي ما لا ينبغي وجوده.</span><span class="en">Each claim is weighed against the correct tariff and quietly screened for anything that shouldn't be there.</span></p>
      </div>
      <div class="step">
        <span class="step-num">03</span>
        <h3><span class="ar">يتشكّل التقرير الشهري</span><span class="en">A monthly report takes shape</span></h3>
        <p><span class="ar">تُجمَع المطالبات المدقَّقة في تقرير واحد لمؤسستكم، مع تفصيل أي بند مرصود على حدة.</span><span class="en">Audited claims are gathered into a single report for your organization, with anything flagged set apart and explained.</span></p>
      </div>
      <div class="step">
        <span class="step-num">04</span>
        <h3><span class="ar">يتبع الدفع، وفق وتيرتكم</span><span class="en">Payment follows, on your terms</span></h3>
        <p><span class="ar">تسددون للمزوّد مباشرة، مسترشدين بتقريرنا — ونحن نقف خلف كل رقم.</span><span class="en">You settle with the provider directly, guided by our report — we stand behind the numbers.</span></p>
      </div>
    </div>
  </div>
</section>

<!-- ==================== NETWORK BAND ==================== -->
<section class="band" id="network">
  <div class="wrap">
    <div class="stat fade-in"><span class="num">280+</span><span class="label"><span class="ar">مزود رعاية صحية على مستوى البلاد</span><span class="en">Healthcare providers nationwide</span></span></div>
    <div class="stat fade-in"><span class="num">14</span><span class="label"><span class="ar">مدينة </span><span class="en">Cities with direct billing</span></span></div>
    <div class="stat fade-in"><span class="num">60K+</span><span class="label"><span class="ar">عضو مؤمَّن عليه حاليًا</span><span class="en">Members currently covered</span></span></div>
    <div class="stat fade-in"><span class="num"><span class="ar">7 أعوام</span><span class="en">7 yrs</span></span><span class="label"><span class="ar">من العمل في ليبيا</span><span class="en">Operating in Libya</span></span></div>
  </div>
</section>

<!-- ==================== TESTIMONIAL ==================== -->
<section>
  <div class="wrap testimonial fade-in">
    <div class="eyebrow" style="justify-content:center"><span class="ar">بصوتهم</span><span class="en">In their words</span></div>
    <blockquote style="margin-top:22px">
      <span class="ar">"كانت مطالبات فريقنا تستغرق أسابيع. مع المساندة، يفوتر المستشفى مباشرة — بالكاد نفكر في الأمر الآن."</span>
      <span class="en">"Our team's claims used to take weeks. With Almusanada, the hospital bills them directly — we barely think about it anymore."</span>
    </blockquote>
    <div class="who">
      <span class="ar">أسامة سالم <span>— مدير الموارد البشرية، عضو مؤسسي منذ 2022</span></span>
      <span class="en">Osama Salem <span>— HR Director, corporate member since 2022</span></span>
    </div>
  </div>
</section>

<!-- ==================== CTA ==================== -->
<section class="cta-band" id="contact">
  <div class="wrap fade-in">
    <div class="eyebrow" style="justify-content:center"><span class="ar">أوقفوا استنزاف ميزانيتكم</span><span class="en">Stop the drain</span></div>
    <h2 style="margin-top:14px"><span class="ar">تحدث مع فريق الرقابة والشراكات لدينا</span><span class="en">Talk to our oversight &amp; partnerships team</span></h2>
    <p><span class="ar">أخبرنا بحجم مطالباتك السنوية — سنوضح كيف تراجع رقابتنا كل فاتورة وتوقف الإنفاق غير المبرر.</span><span class="en">Tell us your annual claims volume — we'll show you how our oversight checks every invoice and stops unwarranted spend.</span></p>
    <div class="cta-actions">
      <a href="mailto:info@almusanada.ly" class="btn btn-primary"><span class="ar">تواصل معنا</span><span class="en">Contact us</span></a>
     <a href="tel:+218934278955" class="btn btn-ghost">
    <span class="ar">اتصل <bdi class="en-num">+218 93 427 89 55</bdi></span>
    <span class="en">Call +218 93 427 89 55</span>
</a>
</a>
    </div>
  </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <span class="footer-logo">
          <img src="{{ asset('images/logo.png') }}" alt="Almusanada Insurance" style="height:38px;width:auto;object-fit:contain;">
          <span>
            <span class="ar">شركة المساندة للتأمين</span>
            <span class="en">Almusanada</span>
            <span class="sub-line"><span class="ar">ALMUSANADA INSURANCE</span><span class="en">شركة المساندة للتأمين</span></span>
          </span>
        </span>
        <p style="font-size:13.5px;max-width:280px;color:#8FA2B5">
          <span class="ar">رقابة على المطالبات الطبية وضبط للإنفاق لشركات التأمين والشركات في جميع أنحاء ليبيا.</span>
          <span class="en">Medical claims oversight and spend control for insurers and companies across Libya.</span>
        </p>
      </div>
      <div>
        <h4><span class="ar">خدماتنا</span><span class="en">Services</span></h4>
        <ul>
          <li><a href="#services"><span class="ar">إدارة الأطراف الثالثة</span><span class="en">TPA administration</span></a></li>
          <li><a href="#services"><span class="ar">تحليل البيانات بالذكاء الاصطناعي</span><span class="en">AI data analysis</span></a></li>
          <li><a href="#network"><span class="ar">شبكة المزودين</span><span class="en">Provider network</span></a></li>
        </ul>
      </div>
      <div>
        <h4><span class="ar">الشركة</span><span class="en">Company</span></h4>
        <ul>
          <li><a href="#network"><span class="ar">شبكة المزودين</span><span class="en">Provider network</span></a></li>
          <li><a href="#process"><span class="ar">كيف تعمل المطالبات</span><span class="en">How claims work</span></a></li>
          <li><a href="#contact"><span class="ar">تواصل معنا</span><span class="en">Contact</span></a></li>
        </ul>
      </div>
      <div>
        <h4><span class="ar">تواصل معنا</span><span class="en">Contact</span></h4>
        <ul>
          <li><span class="ar">طرابلس، ليبيا</span><span class="en">Tripoli, Libya</span></li>
          <li><span class="ar">عام — info@almusanada.ly</span><span class="en">Info — info@almusanada.ly</span></li>
          <li><span class="ar">عام — almusanada_inco@outlook.com</span><span class="en">General — almusanada_inco@outlook.com</span></li>
          <li>saned@almusanada.ly</li>
          <li><span class="ar">الرئيس التنفيذي — ceo@almusanada.ly</span><span class="en">CEO — ceo@almusanada.ly</span></li>
         <li><bdi dir="ltr">+218 93 427 89 55</bdi></li>
<li><bdi dir="ltr">+218 92 021 22 22</bdi></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span><span class="ar">© 2026 شركة المساندة للتأمين. جميع الحقوق محفوظة.</span><span class="en">© 2026 Almusanada Medical Insurance. All rights reserved.</span></span>
      <span><span class="ar">شركة تأمين مرخصة، ليبيا</span><span class="en">Licensed insurer, Libya</span></span>
    </div>
  </div>
</footer>

<button class="back-top" id="backTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Top">↑</button>

<script>
/* ===== Language toggle ===== */
function toggleLang(){
  var h = document.documentElement;
  var cur = h.getAttribute('data-lang');
  var nxt = cur === 'ar' ? 'en' : 'ar';
  h.setAttribute('data-lang', nxt);
  h.setAttribute('lang', nxt);
  h.setAttribute('dir', nxt === 'ar' ? 'rtl' : 'ltr');
  document.title = nxt === 'ar' ? 'شركة المساندة للتأمين' : 'Almusanada Medical Insurance';
}

/* ===== Belief diamond flip ===== */
(function(){
  var ar = ["الثقة","النزاهة","الكفاءة","المصداقية","التطوير الذكي"];
  var en = ["Trust","Integrity","Efficiency","Credibility","Smart Development"];
  var idx = 0, el = document.getElementById('beliefWord');
  if(!el) return;
  setInterval(function(){
    el.classList.add('flip');
    setTimeout(function(){
      idx = (idx + 1) % ar.length;
      el.textContent = document.documentElement.getAttribute('data-lang') === 'ar' ? ar[idx] : en[idx];
    }, 320);
    setTimeout(function(){ el.classList.remove('flip'); }, 700);
  }, 2400);
})();

/* ===== Fade in on scroll ===== */
(function(){
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
    });
  }, {threshold:0.15, rootMargin:'0px 0px -40px 0px'});
  document.querySelectorAll('.fade-in').forEach(function(el){ obs.observe(el); });
})();

/* ===== Animate bars ===== */
(function(){
  var bars = document.querySelectorAll('.bar-fill'), done = false;
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting && !done){
        done = true;
        bars.forEach(function(b){ var w = b.getAttribute('data-width'); if(w) b.style.width = w + '%'; });
      }
    });
  }, {threshold:0.3});
  if(bars.length) obs.observe(bars[0].closest('.coverage-card'));
})();

/* ===== Back to top ===== */
(function(){
  var btn = document.getElementById('backTop');
  window.addEventListener('scroll', function(){
    btn.classList.toggle('show', window.scrollY > 500);
  });
})();

/* ===== Mobile menu ===== */
function openMobile(){
  document.getElementById('mobileMenu').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeMobile(){
  document.getElementById('mobileMenu').classList.remove('open');
  document.body.style.overflow = '';
}
</script>
</body>
</html>
