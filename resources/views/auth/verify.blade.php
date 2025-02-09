<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
</head>

<body>
    <h1>Verifikasi Email Anda</h1>
    <p>Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.</p>
    <p>Jika Anda tidak menerima email, klik tombol
    </p>
    @if (Auth::user() && !Auth::user()->hasVerifiedEmail())
        <div>
            Email Anda belum terverifikasi.
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit">Kirim Ulang Email Verifikasi</button>
            </form>
        </div>
    @endif
</body>

</html>