<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Settings\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

class LogoutAction extends Action
{

    protected function action(): Response
    {
        try {
            $token = $this->request->getHeader("authorization");
            $token = \trim((string) \preg_replace('/^\s*Bearer\s/', '', $token[0]));
            $sql = "SELECT user_id FROM tokens WHERE access_token='" . $token . "'";
            $db = new DB();
            $conn = $db->connect();
            $user_id = $conn->query($sql);
            if ($user_id->rowCount() > 0) {
                $conn->query("DELETE FROM tokens WHERE user_id=" . $user_id->fetch()["user_id"]);
            }
            $db = null;
            return $this->respondWithData(["result" => true]);
        } catch (Throwable $ex) {
            return $this->respondWithData(["result" => false, "message" => $ex->getMessage()], 500);
        }
    }
}