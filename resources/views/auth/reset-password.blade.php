<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
</head>

<body>
    <form action="{{route("password.update")}}" method="post">
        @csrf

        <input type="hidden" name="token" value="{{$token}}">

        <label for="email">email</label>
        <input type="email" id="email" name="email">

        <label for="password">password</label>
        <input type="password" id="password" name="password">

        <label for="password_verify">verify password</label>
        <input type="password" id="password_verify" name="password_confirmation">

        <input type="submit">
    </form>
</body>

</html>