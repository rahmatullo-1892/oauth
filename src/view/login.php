<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/app.css" rel="stylesheet">
    <script src="js/var.js"></script>
    <title>Авторизация</title>
    <style>
        .main {
            width: 400px;
            left: 0;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="main">
        <div class="alert-danger mb-1" id="alert"></div>
        <form method="get" autocomplete="off" action="javascript:void(0)">
            <div class="field">
                <label for="login">Логин</label>
                <input type="text" id="login" placeholder="tom">
            </div>
            <div class="field">
                <label for="password">Пароль</label>
                <input type="password" id="password" placeholder="******">
            </div>
            <div>
                <a href="registration">Регистрация</a>
                <button onclick="auth()" class="float-end">Авторизация</button>
            </div>
        </form>
    </div>
</div>
</body>

<script src="js/main.js"></script>
<script src="js/login.js"></script>
</html>
