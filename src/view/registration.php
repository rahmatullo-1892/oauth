<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/app.css" rel="stylesheet">
    <script src="js/var.js"></script>
    <title>Регистрация</title>
    <style>
        .main {
            max-width: 400px;
            left: 0;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="main">
        <form method="get" autocomplete="off">
            <div class="alert-danger" id="alert"></div>
            <div class="field">
                <label for="name">ФИО</label>
                <input type="text" id="name" placeholder="Tomas">
            </div>
            <div class="field">
                <label for="login">Логин</label>
                <input type="text" id="login" placeholder="tom">
            </div>
            <div class="field">
                <label for="password">Пароль</label>
                <input type="password" id="password" placeholder="******">
            </div>
            <div class="field">
                <label for="repeat">Повторите пароль</label>
                <input type="password" id="repeat" placeholder="******">
            </div>
            <div>
                <a href="signin">Авторизация</a>
                <button onclick="signup()" class="float-end" type="button">Регистрация</button>
            </div>
        </form>
    </div>
</div>
</body>

<script src="js/signup.js"></script>
</html>
