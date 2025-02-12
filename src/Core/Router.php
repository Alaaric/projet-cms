<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $controllerAction, ?string $authorization = null)
    {
        $this->addRoute('GET', $uri, $controllerAction, $authorization);
    }

    public function post(string $uri, string $controllerAction, ?string $authorization = null)
    {
        $this->addRoute('POST', $uri, $controllerAction, $authorization);
    }

    private function addRoute(string $method, string $uri, string $controllerAction, ?string $authorization)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controllerAction' => $controllerAction,
            'role' => $authorization ?? 'public'
        ];
    }

    public function route(string $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->convertToRegex($route['uri']), $uri, $matches)) {

                if (!$this->checkPermissions($route['role'])) {
                    $this->renderError('Accès interdit !', 403);
                    return;
                }

                list($controllerName, $method) = explode('@', $route['controllerAction']);
                $controller = "App\\Controllers\\$controllerName";
                $controller = new $controller();

                array_shift($matches);
                call_user_func_array([$controller, $method],array_values($matches));
                return;
            }
        }

        $this->renderError('Page non trouvée!', 404);
    }

    private function convertToRegex($uri)
    {
        return "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri) . "$#";
    }

    private function checkPermissions(string $requiredAuth): bool
    {
        if ($requiredAuth === 'public') {
            return true;
        }

        if ($requiredAuth === 'user' && isset($_SESSION['user'])) {
            return true;
        }

        if ($requiredAuth === 'admin' && $_SESSION['user']['role'] === 'admin') {
            return true;
        }

        return false;
    }

    private function renderError(string $message, int $statusCode)
    {
        http_response_code($statusCode);
        if ($statusCode === 404) {
            require_once "../src/Views/404.php";
        } else {
            require_once "../src/Views/error.php";
        }
    }
}
