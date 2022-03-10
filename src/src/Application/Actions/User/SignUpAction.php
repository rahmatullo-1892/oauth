<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Settings\DB;
use Awurth\SlimValidation\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use Throwable;

class SignUpAction extends Action
{
    protected function action(): Response
    {
        try {
            $validation = new Validator();
            $request = $this->request->getParsedBody();
            $check = $validation->validate($request, [
                "name" => [
                    "rules" => V::length(4),
                    "message" => "Имя должно быть не менее 4 символов"
                ],
                "login" => [
                    "rules" => V::length(4),
                    "message" => "Логин должен быть не менее 4 символов"
                ],
                "password" => [
                    "rules" => V::length(4),
                    "message" => "Пароль должен быть не менее 4 символов"
                ],
                "repeat" => [
                    "rules" => V::equals($request["password"]),
                    "message" => "Пароли не совпадают"
                ]
            ]);
            if ($check->isValid()) {
                $db = new DB();
                $conn = $db->connect();
                $check = $conn->query("SELECT id FROM users WHERE login='" . $request["login"] . "'");
                if ($check->rowCount() == 0) {
                    $row = [
                        "name"      => $request["name"],
                        "login"     => $request["login"],
                        "password"  => password_hash($request["password"], PASSWORD_ARGON2I),
                    ];
                    $sql = "INSERT INTO users SET name=:name, login=:login, password=:password;";
                    $conn->prepare($sql)->execute($row);
                    $data = ["result" => true];
                } else {
                    $data = ["result" => false, "message" => "Этот логин уже занят"];
                }
            } else {
                $data = ["result" => false, "message" => $this->strError($validation->getErrors())];
            }
        } catch (Throwable $ex) {
            $data = ["result" => false, "message" => $ex->getMessage()];
        }
        $db = null;

        return $this->respondWithData($data);
    }
}