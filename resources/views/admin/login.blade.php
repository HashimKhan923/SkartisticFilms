<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SK Artistic Films</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #c9a84c;
            --dark: #080808;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--dark);
            color: #e8e8e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated background */
        .bg {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(201,168,76,.07) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 20%, rgba(201,168,76,.04) 0%, transparent 50%);
            animation: bgShift 8s ease-in-out infinite alternate;
        }

        @keyframes bgShift {
            from { transform: scale(1); }
            to   { transform: scale(1.05); }
        }

        .card {
            position: relative;
            z-index: 10;
            width: 440px;
            padding: 56px 48px;
            border: 1px solid rgba(201,168,76,.15);
            background: rgba(14,14,14,.95);
            backdrop-filter: blur(20px);
            animation: fadeIn .6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 40px; right: 40px;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 400;
            letter-spacing: .06em;
        }

        .logo-text span { color: var(--gold); }

        .logo-sub {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .4em;
            text-transform: uppercase;
            color: rgba(255,255,255,.25);
            margin-top: 6px;
        }

        .divider {
            width: 40px;
            height: 1px;
            background: var(--gold);
            margin: 20px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: rgba(255,255,255,.4);
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            color: #e8e8e0;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color .3s;
        }

        input:focus { border-color: rgba(201,168,76,.4); }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .remember-row input[type="checkbox"] {
            accent-color: var(--gold);
        }

        .remember-row label {
            font-size: 12px;
            letter-spacing: .05em;
            text-transform: none;
            color: rgba(255,255,255,.4);
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--gold);
            color: #080808;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .25em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all .3s;
        }

        .btn-login:hover {
            background: #e8c97a;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(201,168,76,.3);
        }

        .error-msg {
            background: rgba(220,50,50,.1);
            border: 1px solid rgba(220,50,50,.3);
            color: #f87171;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: rgba(255,255,255,.25);
            letter-spacing: .1em;
            transition: color .3s;
        }

        .back-link:hover { color: var(--gold); }
    </style>
</head>
<body>
    <div class="bg"></div>

    <div class="card">
        <div class="logo">
            <div class="logo-text">SK <span>Artistic</span> Films</div>
            <div class="logo-sub">Admin Portal</div>
            <div class="divider"></div>
        </div>

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">← Back to Website</a>
    </div>
</body>
</html>