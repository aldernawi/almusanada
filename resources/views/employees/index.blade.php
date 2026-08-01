<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Almusanada Team - Meet our professional team members">
    <title>Our Team - Almusanada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ===== NAV ===== */
        nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 38px;
            height: 38px;
            background: #0f172a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-brand-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .nav-brand-text {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
        }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .nav-back:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .nav-back svg {
            width: 14px;
            height: 14px;
        }

        /* ===== HERO ===== */
        .page-hero {
            background: linear-gradient(160deg, #f0f7ff 0%, #ffffff 60%);
            padding: 4rem 2rem 3rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .hero-label {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.25rem;
            letter-spacing: 1px;
        }

        .page-hero h1 {
            font-size: 2.75rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        .page-hero p {
            font-size: 1.05rem;
            color: #64748b;
        }

        /* ===== GRID ===== */
        .team-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.75rem;
        }

        /* ===== CARD ===== */
        .team-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .card-top {
            height: 80px;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            position: relative;
        }

        .card-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid white;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            color: white;
            position: absolute;
            bottom: -45px;
            right: 50%;
            transform: translateX(50%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 3.5rem 1.75rem 2rem;
            text-align: center;
        }

        .card-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .card-position {
            font-size: 0.875rem;
            color: #1d4ed8;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .qr-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .qr-container img {
            display: block;
            border-radius: 6px;
        }

        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-view {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #0f172a;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.25s;
        }

        .btn-view:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-view svg {
            width: 16px;
            height: 16px;
        }

        .btn-print {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 10px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.25s;
            width: 100%;
        }

        .btn-print:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-print svg {
            width: 16px;
            height: 16px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
            color: #94a3b8;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
            font-size: 0.875rem;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 640px) {
            .page-hero h1 {
                font-size: 2rem;
            }

            .team-section {
                padding: 2.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="nav-brand-text">Almusanada</span>
            </a>
            <a href="{{ url('/') }}" class="nav-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>
        </div>
    </nav>

    <!-- Hero -->
    <div class="page-hero">
        <div class="hero-label">👥 Team</div>
        <h1>Our Team</h1>
        <p>Meet our distinguished professional team members</p>
    </div>

    <!-- Team Grid -->
    <div class="team-section">
        @if($employees->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3>No team members currently</h3>
                <p>Team members will be added soon.</p>
            </div>
        @else
            <div class="team-grid">
                @foreach($employees as $employee)
                    <div class="team-card">
                        <div class="card-top"></div>
                        <div class="card-avatar">
                            @if($employee->image_path)
                                <img src="{{ asset($employee->image_path) }}" alt="{{ $employee->name }}">
                            @else
                                {{ mb_substr($employee->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="card-body">
                            <h2 class="card-name">{{ $employee->name }}</h2>
                            <p class="card-position">{{ $employee->position }}</p>

                            <div class="qr-container">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ route('employee.show', $employee->id) }}"
                                    alt="QR - {{ $employee->name }}" width="120" height="120">
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('employee.show', $employee->id) }}" class="btn-view">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm7.5 0c0 4.97-6.716 9-10.5 9S1.5 16.97 1.5 12 8.216 3 12 3s10.5 4.03 10.5 9z"/>
                                    </svg>
                                    View Profile
                                </a>
                                <button onclick="printQR('{{ $employee->id }}', '{{ $employee->name }}')" class="btn-print">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    Print QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <footer>
        <p>© {{ date('Y') }} Almusanada - All rights reserved</p>
    </footer>

    <script>
        function printQR(id, name) {
            const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ url('/employee') }}/" + id;
            const win = window.open('', '_blank');
            win.document.write(`
                <!DOCTYPE html>
                <html lang="en" dir="ltr">
                <head>
                    <meta charset="UTF-8">
                    <title>QR Code - ${name}</title>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
                    <style>
                        body {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            font-family: 'Inter', sans-serif;
                            margin: 0;
                            background: #f8fafc;
                        }
                        .card {
                            background: white;
                            padding: 2.5rem;
                            border-radius: 20px;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                            text-align: center;
                            border: 2px solid #e2e8f0;
                        }
                        .brand {
                            font-size: 0.85rem;
                            font-weight: 700;
                            color: #64748b;
                            margin-bottom: 0.5rem;
                            text-transform: uppercase;
                            letter-spacing: 2px;
                        }
                        h1 {
                            font-size: 1.5rem;
                            font-weight: 800;
                            color: #0f172a;
                            margin-bottom: 1.5rem;
                        }
                        img {
                            border: 6px solid #f1f5f9;
                            border-radius: 12px;
                            padding: 0.75rem;
                            background: white;
                        }
                        .footer {
                            margin-top: 1rem;
                            font-size: 0.8rem;
                            color: #94a3b8;
                        }
                    </style>
                </head>
                <body onload="window.print();window.close()">
                    <div class="card">
                        <div class="brand">Almusanada</div>
                        <h1>${name}</h1>
                        <img src="${qrUrl}" alt="QR Code" />
                        <div class="footer">Scan the code to view the profile</div>
                    </div>
                </body>
                </html>
            `);
            win.document.close();
        }
    </script>
</body>

</html>