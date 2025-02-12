<?php

namespace App\Controllers;

abstract class AbstractController
{

    protected function render(string $view, array $data = [])
    {
        extract($data);

        ob_start();
        require_once "../src/views/$view.php";
        $content = ob_get_clean();

        require_once "../src/views/main.php";
    }
    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }


    protected function isRequestMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }

    protected function getInput(string $key = null)
    {
        $input = $_POST;
        if ($key) {
            return $input[$key] ?? null;
        }
        return $input;
    }
}
