<!DOCTYPE html> 
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 320px;
            padding: 25px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .alert {
            background-color: #ffdddd;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #0d6efd;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update.custom') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="whatsapp" value="{{ $whatsapp }}">

            <div>
                <label>Password Baru</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit">Reset Password</button>
        </form>

        <a href="{{ route('login') }}">
            <button type="button" style="background-color: #6c757d; color: white; border: none; padding: 10px; margin-top: 10px; border-radius: 20px; width: 100%;">
                ← Kembali ke Login
            </button>
        </a>
    </div>
</body>
</html>
