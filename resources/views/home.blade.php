<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{route("logout")}}" method="post">
        @csrf
        <button type="submit">logout</button>
    </form>
    <h1>hello world</h1>

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