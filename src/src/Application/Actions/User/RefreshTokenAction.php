<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Repositories\AccessTokenRepository;
use App\Application\Repositories\ClientRepository;
use App\Application\Repositories\RefreshTokenRepository;
use App\Application\Repositories\ScopeRepository;
use App\Application\Settings\DB;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

class RefreshTokenAction extends UserAction
{
    protected function action(): Response
    {
        try {
            $token = $this->request->getHeader("authorization");
            $refresh = $this->request->getParsedBody()["refresh_token"];
            $token = \trim((string) \preg_replace('/^\s*Bearer\s/', '', $token[0]));
            $user_id = $this->getUserId($token);
            if (!$this->isTokenActive($token, $refresh) && $user_id != 0) {
                // Setup the authorization server
                $server = new AuthorizationServer(
                    new ClientRepository(),
                    new AccessTokenRepository(),
                    new ScopeRepository(),
                    new CryptKey('file://' . __DIR__ . '/../../keys/private.key', null, false),
                    'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen'
                );

                // Enable the refresh token grant on the server
                $grant = new RefreshTokenGrant(new RefreshTokenRepository());
                $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // The refresh token will expire in 1 month

                $server->enableGrantType(
                    $grant,
                    new \DateInterval('PT1H') // The new access token will expire after 1 hour
                );

                try {
                    $result = $server->respondToAccessTokenRequest($this->request, $this->response);
                    $json  = json_decode($result->getBody());
                    if (!empty($json->access_token)) {
                        $this->addToken($json, $user_id);
                    }
                    return $result;
                } catch (OAuthServerException $exception) {
                    return $exception->generateHttpResponse($this->response);
                } catch (Exception $exception) {
                    $this->response->getBody()->write($exception->getMessage());
                    return $this->response->withStatus(500);
                }
            } else {
                if ($user_id == 0) {
                    return $this->respondWithData(["result" => false], 200);
                } else {
                    return $this->respondWithData(["result" => true], 200);
                }
            }
        } catch (Throwable $ex) {
            return $this->respondWithData(["result" => false, "message" => $ex->getMessage()]);
        }
    }

    private function isTokenActive($token, $refresh): bool
    {
        $sql = "SELECT id FROM tokens WHERE refresh_token='" . $refresh . "' AND expired_at>='" . date("Y-m-d H:i:s") . "'";
        $db = new DB();
        $conn = $db->connect();
        $check = $conn->query($sql);
        $db = null;
        return $check->rowCount() > 0;
    }

    private function getUserId($token)
    {
        $sql = "SELECT user_id FROM tokens WHERE access_token='" . $token . "'";
        $db = new DB();
        $conn = $db->connect();
        $user_id = $conn->query($sql);
        $db = null;
        if ($user_id->rowCount() > 0) {
            return $user_id->fetch()["user_id"];
        }
        return 0;
    }
}