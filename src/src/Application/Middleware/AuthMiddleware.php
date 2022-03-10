<?php

namespace App\Application\Middleware;

use App\Application\Repositories\AccessTokenRepository;
use App\Application\Settings\DB;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        // Path to authorization server's public key
        $publicKeyPath = new CryptKey('file://' . __DIR__ . '/../keys/public.key', null, false);

        // Setup the authorization server
        $server = new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $token = $request->getHeader("Cookie")[0];
                $token = substr($token, strpos($token,"Access-Token") + 13);
                $token = substr($token, 0,strpos($token,";"));
            } else {
                $token = $request->getHeader("authorization");
                $token = \trim((string) \preg_replace('/^\s*Bearer\s/', '', $token[0]));
            }

            $server->validateAuthenticatedRequest($request);

            if (!empty($token)) {
                if ($this->checkDB($token)) {
                    return $handler->handle($request);
                }
                return $this->error("You session was expired. Please authorize again.");
            }
            return $this->error("You didn't authorized in system");
        } catch (OAuthServerException $ex) {
            return $this->error($ex->getMessage());
        }
    }

    private function checkDB($token): bool
    {
        $sql = "SELECT id FROM tokens WHERE access_token='" . $token . "'";
        $db = new DB();
        $conn = $db->connect();
        $check = $conn->query($sql)->rowCount() > 0;
        $db = null;
        return $check;
    }

    private function error($text)
    {
        $response = new Response();
        $response->getBody()->write(json_encode(["result" => false, "message" => $text]));
        return $response->withStatus(302);
//        return $response->withHeader('Location', 'http://localhost/slimfront/login.html')->withStatus(302);
    }
}
