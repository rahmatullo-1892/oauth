<?php

namespace App\Application\Actions\User;

use App\Application\Settings\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

class ProfileAction extends UserAction {

    protected function action(): Response
    {
        try {
            $token = $this->request->getHeader("authorization");
            $token = \trim((string) \preg_replace('/^\s*Bearer\s/', '', $token[0]));
            $db = new DB();
            $conn = $db->connect();
            $user = $conn->query("SELECT user_id FROM tokens WHERE access_token='" . $token . "'");
            $db = null;
            if ($user->rowCount() > 0) {
                $user_id = $user->fetch()["user_id"];
                $user = $conn->query("SELECT * FROM users WHERE id=" . $user_id)->fetch();
                $data = [
                    "name" => $user["name"],
                    "login" => $user["login"],
                    "created_at" => date("d-m-Y H:i", strtotime($user["created_at"])),
                ];
                return $this->respondWithData(["result" => true, $data]);
            } else {
                return $this->respondWithData(["result" => false, "message" => "User not found"]);
            }
        } catch (Throwable $ex) {
            return $this->respondWithData(["result" => false, "message" => $ex->getMessage()], 500);
        }
    }
}