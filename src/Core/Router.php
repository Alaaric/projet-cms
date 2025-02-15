<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private const ERROR_FORBIDDEN = 403;
    private const ERROR_NOT_FOUND = 404;
    private const ERROR_INTERNAL_SERVER = 500;

    private const ROLE_PUBLIC = 'public';
    private const ROLE_USER = 'user';
    private const ROLE_ADMIN = 'admin';

    public function get(string $uri, string $controllerAction, ?string $authorization = null): void
    {
        $this->addRoute(self::METHOD_GET, $uri, $controllerAction, $authorization);
    }

    public function post(string $uri, string $controllerAction, ?string $authorization = null): void
    {
        $this->addRoute(self::METHOD_POST, $uri, $controllerAction, $authorization);
    }

    private function addRoute(string $method, string $uri, string $controllerAction, ?string $authorization): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controllerAction' => $controllerAction,
            'role' => $authorization ?? self::ROLE_PUBLIC
        ];
    }

    public function route(string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $uri)) {
                if (!$this->checkPermissions($route['role'])) {
                    $this->renderError('Accès interdit !', self::ERROR_FORBIDDEN);
                    return;
                }

                $this->dispatch($route['controllerAction'], $route['uri'], $uri);
                return;
            }
        }

        $this->renderError('Page non trouvée!', self::ERROR_NOT_FOUND);
    }

    private function matchRoute(array $route, string $method, string $uri): bool
    {
        return $route['method'] === $method && preg_match($this->convertToRegex($route['uri']), $uri);
    }

    private function convertToRegex(string $uri): string
    {
        return "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri) . "$#";
    }

    private function checkPermissions(string $requiredAuth): bool
    {
        return match ($requiredAuth) {
            self::ROLE_PUBLIC => true,
            self::ROLE_USER => isset($_SESSION['user']),
            self::ROLE_ADMIN => isset($_SESSION['user']) && $_SESSION['user']['role'] === self::ROLE_ADMIN,
            default => false,
        };
    }

    private function renderError(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        $view = match ($statusCode) {
            self::ERROR_FORBIDDEN => "../src/Views/403.php",
            self::ERROR_NOT_FOUND => "../src/Views/404.php",
            default => "../src/Views/error.php",
        };
        require_once $view;
    }

    private function dispatch(string $controllerAction, string $routeUri, string $requestUri): void
    {
        list($controllerName, $method) = explode('@', $controllerAction);
        $controller = "App\\Controllers\\$controllerName";
        $controller = new $controller();

        preg_match($this->convertToRegex($routeUri), $requestUri, $matches);
        array_shift($matches);

        call_user_func_array([$controller, $method], array_values($matches));
    }
}