<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #bbdefb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 900px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .left-panel {
            flex: 1.2;
            background: url('{{ asset("images/header login.jpg") }}') no-repeat center;
            background-size: cover;
            min-height: 450px;
        }

        .right-panel {
            flex: 0.8;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #333;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #1976d2;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1565c0;
        }

        .link {
            text-align: right;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .link a {
            color: #1976d2;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
            }

            .left-panel {
                min-height: 200px;
            }

            .right-panel {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left-panel"></div>
    <div class="right-panel">
        <h2>Selamat Datang, Admin</h2>

        {{-- Tampilkan error jika ada --}}
        @if(session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        {{-- Tampilkan pesan sukses jika ada --}}
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>

            <div class="link">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</div>

</body>
</html>
