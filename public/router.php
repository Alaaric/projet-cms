<?php
session_start();

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Router;
use App\Controllers\PageController;

$router = new Router();
$controller = new PageController();

$router->get("/", [$controller, "index"]);
$router->get("/create", [$controller, "create"]);
$router->post("/create", [$controller, "create"]);

$router->get("/login", [$authController, "login"]);
$router->post("/login", [$authController, "login"]);
$router->get("/logout", [$authController, "logout"]);

$router->resolve();
