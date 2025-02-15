<?php

namespace App\Controllers;

use App\Exceptions\Controllers\RenderException;
use App\Exceptions\Controllers\RedirectException;
use App\Exceptions\Controllers\RequestMethodException;
use App\Exceptions\Controllers\InputException;

abstract class AbstractController
{
    protected function render(string $view, array $data = [])
    {
        try {
            extract($data);
            ob_start();
            require_once "../src/views/$view.php";
            $content = ob_get_clean();
            require_once "../src/views/main.php";
        } catch (\Exception $e) {
            throw new RenderException("Erreur lors du rendu de la vue : $view", 500, $e);
        }
    }

    protected function redirect(string $url)
    {
        try {
            header("Location: $url");
            exit;
        } catch (\Exception $e) {
            throw new RedirectException("Erreur lors de la redirection vers : $url", 500, $e);
        }
    }

    protected function isRequestMethod(string $method): bool
    {
        try {
            return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
        } catch (\Exception $e) {
            throw new RequestMethodException("Erreur lors de la vérification de la méthode de requête : $method", 405, $e);
        }
    }

    protected function getInput(string $key = null)
    {
        try {
            $input = $_POST;
            if ($key) {
                return $input[$key] ?? null;
            }
            return $input;
        } catch (\Exception $e) {
            throw new InputException("Erreur lors de la récupération des données d'entrée", 500, $e);
        }
    }
}