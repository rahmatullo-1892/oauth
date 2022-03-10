<?php

namespace App\Application\Actions\User;

use App\Application\Repositories\AccessTokenRepository;
use App\Application\Repositories\ClientRepository;
use App\Application\Repositories\RefreshTokenRepository;
use App\Application\Repositories\ScopeRepository;
use App\Application\Repositories\UserRepository;
use DateInterval;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

class AuthorizationAction extends UserAction
{
    public function action(): Response
    {
        try {
            $server = new AuthorizationServer(
                new ClientRepository(),                 // instance of ClientRepositoryInterface
                new AccessTokenRepository(),            // instance of AccessTokenRepositoryInterface
                new ScopeRepository(),                  // instance of ScopeRepositoryInterface
                new CryptKey('file://' . __DIR__ . '/../../keys/private.key', null, false),
                'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'      // encryption key
            );

            $user_repository = new UserRepository();
            $grant = new PasswordGrant(
                $user_repository,           // instance of UserRepositoryInterface
                new RefreshTokenRepository()    // instance of RefreshTokenRepositoryInterface
            );
            $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

            // Enable the password grant on the server with a token TTL of 1 hour
            $server->enableGrantType(
                $grant,
                new DateInterval('PT1H') // access tokens will expire after 1 hour
            );

            try {
                $result = $server->respondToAccessTokenRequest($this->request, $this->response);
                $json  = json_decode($result->getBody());
                if (!empty($json->access_token)) {
                    $this->addToken($json, $user_repository->getId());
                }
                return $result;
            } catch (OAuthServerException $exception) {
                return $this->respondWithData(["result" => false, "message" => $exception->getMessage()], 403);
            } catch (Exception $exception) {
                return $this->respondWithData(["result" => false, "message" => $exception->getTraceAsString()], 500);
            }
        } catch (Throwable $ex) {
            return $this->respondWithData(["result" => false, "message" => $ex->getTrace()], 500);
        }
    }

}
