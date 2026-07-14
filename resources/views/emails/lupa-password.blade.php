<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Notifikasi</title>
</head>
<body>
    <h2>Halo!</h2>
    <p>Anda menerima email ini karena kami menerima permintaan atur ulang (reset) password untuk akun Anda.</p>
    
    <p>Silakan klik tombol di bawah ini untuk mereset password Anda:</p>
    
    <p style="margin: 30px 0;">
        <a href="{{ route('reset-password.index', ['token' => $token, 'email' => $email]) }}" 
           style="background-color: #7367f0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Reset Password
        </a>
    </p>

    <p>Link reset password ini akan kedaluwarsa dalam beberapa menit.</p>
    <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>
    
    <hr>
    <p style="font-size: 12px; color: #777;">
        Jika Anda mengalami masalah saat mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser Anda:
        <br>
        {{ route('reset-password.index', ['token' => $token, 'email' => $email]) }}
    </p>
</body>
</html>