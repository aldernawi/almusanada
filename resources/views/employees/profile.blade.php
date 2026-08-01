<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->name }} - الملف الشخصي</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .header-bg {
            height: 140px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }

        .avatar-container {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            margin: -70px auto 0;
            border: 6px solid white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: white;
            font-weight: 700;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .info {
            padding: 2rem 2.5rem 2.5rem;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .position {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .bio {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 2rem;
            text-align: center;
        }

        .contact-info {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: #4b5563;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-item svg {
            width: 20px;
            height: 20px;
            color: #667eea;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            flex: 1;
            padding: 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }

        .btn-outline {
            border: 2px solid #e5e7eb;
            color: #6b7280;
            background: white;
        }

        .btn-outline:hover {
            border-color: #667eea;
            color: #667eea;
            background: #f9fafb;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.85rem;
            color: #9ca3af;
        }

        @media (max-width: 480px) {
            .profile-card {
                max-width: 100%;
            }

            .info {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="profile-card">
        <div class="header-bg"></div>

        <div class="avatar-container">
            @if($employee->image_path)
                <img src="{{ asset($employee->image_path) }}" alt="{{ $employee->name }}" class="avatar-img">
            @else
                <span>{{ mb_substr($employee->name, 0, 1) }}</span>
            @endif
        </div>

        <div class="info">
            <h1>{{ $employee->name }}</h1>
            <div class="position">{{ $employee->position }}</div>

            @if($employee->bio)
                <p class="bio">{{ $employee->bio }}</p>
            @endif

            <div class="contact-info">
                <div class="contact-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $employee->email }}</span>
                </div>
            </div>

            <div class="actions">
                <a href="mailto:{{ $employee->email }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    إرسال بريد
                </a>
                @if($employee->linkedin)
                    <a href="{{ $employee->linkedin }}" target="_blank" class="btn btn-outline">
                        <svg fill="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                        LinkedIn
                    </a>
                @endif
            </div>

            <div class="footer">
                الشركة المساندة © {{ date('Y') }}
            </div>
        </div>
    </div>
</body>

</html>