<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, callable $callback): void {
        $this->routes["GET"][$path] = $callback;
    }

    public function post(string $path, callable $callback): void {
        $this->routes["POST"][$path] = $callback;
    }

    public function resolve(): void {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REQUEST_URI"] ?? "/";
        $callback = $this->routes[$method][$path] ?? null;
        
        if (!$callback) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        call_user_func($callback);
    }
}
