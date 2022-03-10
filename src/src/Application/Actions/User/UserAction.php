<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Settings\DB;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected UserRepository $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }

    protected function addToken($json, $user_id)
    {
        $row = [
            "user_id"       => $user_id,
            "access_token"  => $json->access_token,
            "refresh_token" => $json->refresh_token,
            "created_at"    => date("Y-m-d H:i:s"),
            "expired_at"    => date("Y-m-d H:i:s", strtotime("+1hour"))
        ];
        $sql = "INSERT INTO tokens SET user_id=:user_id, access_token=:access_token, refresh_token=:refresh_token, created_at=:created_at, expired_at=:expired_at;";
        $db = new DB();
        $conn = $db->connect();
        $conn->prepare($sql)->execute($row);
    }
}
