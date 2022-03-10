<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class CheckSessionAction extends Action
{
    protected function action(): Response
    {
        return $this->respondWithData(["result" => true]);
    }
}
