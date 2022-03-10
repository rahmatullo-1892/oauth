<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/app.css" rel="stylesheet">
    <script src="js/var.js"></script>
    <title>Профиль</title>
    <script>
        let isRefresh = true;
    </script>
</head>
<body>

<div class="wrapper">
    <nav class="sidebar">
        <div class="sidebar-brand">
            <a href="#">My project</a>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="/">
                        <span>Главная</span>
                    </a>
                </li>
                <li class="active">
                    <a href="profile">
                        <span>Профиль</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="logout()">
                        <span>Выход</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <div class="row">
            <span class="label">ФИО</span>
            <span class="value" id="name" ></span>
        </div>
        <div class="row">
            <span class="label">Логин</span>
            <span class="value" id="login" ></span>
        </div>
        <div class="row">
            <span class="label">Дата создания</span>
            <span class="value" id="created_at" ></span>
        </div>
    </div>
</div>
</body>
<script src="js/main.js"></script>
<script src="js/profile.js"></script>
</html>