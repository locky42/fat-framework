<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class RouterService
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = explode('/', trim($args['params'], '/') ?? '');
        $method = array_pop($params) . ucfirst(strtolower($request->getMethod()));
        $controllerClass = ucfirst(array_pop($params)) . 'Controller';
        $namespace = 'App\\Controllers\\';
        $controller = $namespace . $controllerClass;

        try {
            if (!$controller || !method_exists($controller, $method)) {
                throw new HttpNotFoundException($request);
            }
            $object = new $controller();
            $result = $object->$method($request, $response, $args);
        } catch (\Throwable $th) {
            $response->getBody()->write($th->getMessage());
            try {
                $result = $response->withStatus($th->getCode());
            } catch (\Throwable $th) {
                $result = $response->withStatus(500);
            }
        }

        return $result;
    }
}
