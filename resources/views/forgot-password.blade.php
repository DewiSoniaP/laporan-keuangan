<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Lupa Password</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.whatsapp') }}">
            @csrf
            <input type="text" name="whatsapp" placeholder="Nomor WhatsApp" required>
            <button type="submit">Kirim Link Reset</button>
        </form>
    </div>
</body>
</html>
