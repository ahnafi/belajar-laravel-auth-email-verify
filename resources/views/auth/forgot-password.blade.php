<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
</head>

<body>

    <h1>reset password</h1>
    <form action="{{route("password.email")}}" method="post">
        @csrf

        <label for="email">Masukan email</label>
        <input type="email" name="email" id="email">

        <input type="submit">
    </form>

</body>

</html>