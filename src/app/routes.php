<?php

declare(strict_types=1);

use App\Application\Actions\User\AuthorizationAction;
use App\Application\Actions\User\CheckSessionAction;
use App\Application\Actions\User\LogoutAction;
use App\Application\Actions\User\ProfileAction;
use App\Application\Actions\User\RefreshTokenAction;
use App\Application\Actions\User\SignUpAction;
use App\Application\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Views\Twig;

return function (App $app) {

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $renderer = new PhpRenderer('../view');
        return $renderer->render($response, "index.php");
    });

    $app->get('/signin', function (Request $request, Response $response) {
        $renderer = new PhpRenderer('../view');
        return $renderer->render($response, "login.php");
    })->setName("signin");

    $app->get('/registration', function (Request $request, Response $response) {
        $renderer = new PhpRenderer('../view');
        return $renderer->render($response, "registration.php");
    });

    $app->get("/check", CheckSessionAction::class)->add(AuthMiddleware::class);

    $app->post("/auth/signup", SignUpAction::class);

    $app->post("/oauth/auth", AuthorizationAction::class);

    $app->post("/oauth/refresh", RefreshTokenAction::class);

    $app->get('/profile', function (Request $request, Response $response) {
        $renderer = new PhpRenderer('../view');
        return $renderer->render($response, "profile.php");
    });

    $app->post("/app/profile", ProfileAction::class)->add(AuthMiddleware::class);

    $app->post("/oauth/logout", LogoutAction::class)->add(AuthMiddleware::class);
};
