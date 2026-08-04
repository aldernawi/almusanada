<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Almusanada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #080c14 0%, #0f172a 50%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s infinite ease-in-out;
        }
        .orb-1 {
            top: -150px; right: -100px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, transparent 70%);
        }
        .orb-2 {
            bottom: -200px; left: -150px; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.25) 0%, transparent 70%);
            animation-delay: -7s;
        }
        .orb-3 {
            top: 40%; left: 50%; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -40px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(40px, 30px) scale(1.02); }
        }

        /* Grid Pattern Overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrap {
            display: inline-block;
            margin-bottom: 1rem;
            animation: gentlePulse 4s infinite ease-in-out;
        }

        @keyframes gentlePulse {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 20px rgba(37, 99, 235, 0.3)); }
            50% { transform: scale(1.03); filter: drop-shadow(0 0 30px rgba(37, 99, 235, 0.5)); }
        }

        .login-brand h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            letter-spacing: 0.5px;
        }

        .login-brand p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 0.3rem;
            font-weight: 500;
        }

        .login-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.98) 100%);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 0 60px rgba(37, 99, 235, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(37, 99, 235, 0.08);
        }

        .login-card::before {
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

        .login-card::after {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .card-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .card-subtitle {
            font-size: 0.875rem;
            color: #94a3b8;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .status-box {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            border: 1px solid #93c5fd;
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #1e40af;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeIn 0.4s ease;
        }

        .error-box {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        .error-box ul { list-style: none; }
        .error-box li {
            font-size: 0.85rem;
            color: #991b1b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: 0.95rem;
            transition: all 0.25s;
            pointer-events: none;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1), 0 4px 16px rgba(37, 99, 235, 0.12);
            transform: translateY(-1px);
        }

        .form-input:focus + .input-icon {
            color: #2563eb;
            transform: translateY(-50%) scale(1.1);
        }

        .form-input::placeholder { color: #cbd5e1; }

        .pwd-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.2s;
            z-index: 1;
            background: none;
            border: none;
            padding: 0;
        }
        .pwd-toggle:hover { color: #64748b; }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-left input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
            cursor: pointer;
            border-radius: 5px;
        }

        .remember-left label {
            font-size: 0.85rem;
            color: #64748b;
            cursor: pointer;
            font-weight: 600;
        }

        .forgot-link {
            font-size: 0.8rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
        }
        .forgot-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 0.25rem;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
            z-index: 1;
        }

        .btn-login span, .btn-login i { position: relative; z-index: 2; }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.4);
        }
        .btn-login:hover::before { left: 130%; }
        .btn-login:active { transform: translateY(-1px); }
        .btn-login:hover i { transform: translateX(-4px); }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1.75rem;
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.25s;
        }
        .back-link:hover {
            color: rgba(255, 255, 255, 0.9);
            gap: 0.6rem;
        }
        .back-link svg { width: 14px; height: 14px; transition: transform 0.25s; }
        .back-link:hover svg { transform: translateX(-3px); }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1.25rem;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
        }
        .secure-badge i { color: #2563eb; font-size: 0.7rem; }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem; }
            .login-wrapper { max-width: 100%; }
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="login-wrapper">
        <div class="login-brand">
            <div class="logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Almusanada Insurance" style="max-width: 320px; height: auto; filter: brightness(0) invert(1);">
            </div>
        
        </div>

        <div class="login-card">
            <div class="card-title">Login</div>
            <div class="card-subtitle">Enter your credentials to access the dashboard</div>

            @if (session('status'))
                <div class="status-box">
                    <i class="fas fa-info-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrap">
                        <input type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            class="form-input"
                            placeholder="example@email.com"
                            required autofocus autocomplete="username">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password"
                            class="form-input"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="pwd-toggle" onclick="togglePwd(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="remember-row">
                    <div class="remember-left">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </button>
                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure encrypted connection</span>
                </div>
            </form>
        </div>

        <a href="{{ route('home') }}" class="back-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to homepage
        </a>
    </div>

    <script>
        function togglePwd(btn) {
            const input = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>

</html>
