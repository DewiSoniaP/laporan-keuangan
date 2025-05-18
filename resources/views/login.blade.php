<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #a8c9ff;
            display: flex;
            height: 100vh;
        }

        .left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            font-weight: bold;
            color: #0656d4;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }

        .login-box img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            margin: -30px -30px 20px;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .login-box a {
            font-size: 0.9em;
            color: #0d6efd;
            display: block;
            text-align: right;
            margin-bottom: 15px;
            text-decoration: none;
        }

        .login-box button {
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 20px;
            font-size: 1em;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="left">
        Hallo, Admin!
    </div>

    <div class="right">
        <div class="login-box">
            <img src="{{ asset('images/header-login.jpg') }}" alt="Header Image"> {{-- Gambar di atas form --}}
            <h2>Form Login</h2>

            {{-- Tampilkan error jika ada --}}
            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            {{-- Tampilkan pesan sukses jika ada --}}
            @if(session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            {{-- Form login --}}
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Masukkan Email" required>
                <input type="password" name="password" placeholder="Masukkan Password" required>
                <a href="{{ route('password.request') }}">Lupa Password?</a>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
