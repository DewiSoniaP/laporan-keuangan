<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Reset Password</title>
</head>
<body>
    <p>Halo {{ $user->name }},</p>

    <p>Klik link berikut untuk mengatur ulang password Anda:</p>

    <p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>

    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

    <p>Salam,<br>Laporan Keuangan</p>
</body>
</html>
