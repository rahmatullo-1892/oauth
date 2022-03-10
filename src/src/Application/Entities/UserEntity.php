<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Application\Entities;

use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }


    /**
     * Return the user's identifier.
     *
     * @return mixed
     */

    public function getIdentifier()
    {
        return $this->id;
    }
}
