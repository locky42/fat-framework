<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\User;

class UserController
{
    /**
     * @throws Exception
     */
    public function createPost(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = new User();
        $user->create($data);
        return $response->withStatus(201);
    }
}
