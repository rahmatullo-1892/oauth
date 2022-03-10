<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Application\Repositories;

use App\Application\Settings\DB;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Application\Entities\UserEntity;

class UserRepository implements UserRepositoryInterface
{
    private $user_id = 0;

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $sql = "SELECT * FROM users WHERE login='" . $username . "'";
        $db = new DB();
        $conn = $db->connect();
        $user = $conn->query($sql);
        $count = $user->rowCount();
        $user = $user->fetch();
        $db = null;
        if ($count > 0 && password_verify($password, $user["password"])) {
            $this->user_id = $user["id"];
            return new UserEntity($this->user_id);
        }
    }

    public function getId()
    {
        return $this->user_id;
    }
}
