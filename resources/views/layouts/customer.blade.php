<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الاستعلام - الشركة المساندة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #080c14 0%, #0f172a 50%, #1e293b 100%);
            min-height: 100vh;
            color: #333;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Orbs */
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; animation: float 20s infinite ease-in-out; pointer-events: none; }
        .orb-1 { top: -150px; right: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, transparent 70%); }
        .orb-2 { bottom: -200px; left: -150px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(30, 58, 138, 0.25) 0%, transparent 70%); animation-delay: -7s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -40px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(40px, 30px) scale(1.02); }
        }

        /* Top Bar */
        .portal-header {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .portal-brand {
            color: white;
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .portal-brand svg { width: 28px; height: 28px; opacity: 0.9; }

        .portal-nav { display: flex; align-items: center; gap: 1.5rem; }
        .portal-nav a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.3s;
        }
        .portal-nav a:hover { color: white; }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.4rem 1.25rem;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #fee2e2;
            transform: translateY(-1px);
        }

        /* Main Content */
        .portal-content {
            max-width: 700px;
            margin: 3rem auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa, #3b82f6, #2563eb);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .portal-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .portal-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        /* Search Box */
        .search-box { display: flex; gap: 0.75rem; margin-bottom: 2rem; }

        .search-input {
            flex: 1;
            padding: 0.9rem 1.25rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            outline: none;
            background: #f8fafc;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            direction: ltr;
            text-align: right;
        }
        .search-input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
            transform: translateY(-1px);
        }
        .search-input::placeholder { color: #cbd5e1; direction: rtl; }

        .btn-search {
            background: linear-gradient(135deg, #1e293b, #080c14);
            color: white;
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 14px;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-search::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            opacity: 0;
            transition: opacity 0.35s;
        }
        .btn-search span, .btn-search svg { position: relative; z-index: 1; }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        .btn-search:hover::before { opacity: 1; }
        .btn-search svg { width: 20px; height: 20px; }

        /* Result Card */
        .result-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            animation: slideUp 0.4s ease;
        }

        .result-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .result-row:last-child { border-bottom: none; }

        .result-label { color: #64748b; font-weight: 600; font-size: 0.9rem; }
        .result-value { font-weight: 700; color: #1e293b; }
        .result-value.trx-number { color: #2563eb; font-size: 1.1rem; letter-spacing: 1px; direction: ltr; }

        .result-details { padding: 0.75rem 0; }
        .result-details p {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            color: #475569;
            line-height: 1.7;
            margin-top: 0.5rem;
        }

        /* Status Badge */
        .status-badge { padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; }
        .status-processing { background: #fef3c7; color: #92400e; }
        .status-done { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-paused { background: #e5e7eb; color: #374151; }

        /* Error Box */
        .error-box { text-align: center; padding: 2rem; animation: slideUp 0.4s ease; }
        .error-box svg { width: 56px; height: 56px; color: #f87171; margin-bottom: 1rem; }
        .error-box .error-title { color: #dc2626; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem; }
        .error-box .error-desc { color: #9ca3af; font-size: 0.85rem; }

        /* Footer */
        .portal-footer {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.8rem;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 640px) {
            .search-box { flex-direction: column; }
            .portal-card { padding: 1.5rem; }
            .portal-content { margin: 1.5rem auto; }
            .portal-header { flex-direction: column; gap: 1rem; }
            .result-row { flex-direction: column; align-items: flex-start; gap: 0.25rem; }
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    @yield('content')

    <div class="portal-footer">
        &copy; {{ date('Y') }} الشركة المساندة - بوابة الاستعلام
    </div>
</body>

</html>