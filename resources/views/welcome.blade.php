<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prueba</title>
</head>
<body>
    <form action="/login" method="POST">
        @csrf
    <input type="text" name="email">

    <input type="password" name="password">

    <button type="submit">Dale</button>
    
    </form>
</body>
</html>