<?php
session_start();

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Router;

$router = new Router();

$router->get("/",  "PageController@index");
$router->get("/page/{slug}",  "PageController@show");
$router->get("/create", "PageController@create", "user");
$router->post("/create", "PageController@create", "user");
$router->get('/page/edit/{slug}', 'PageController@edit', 'user');
$router->post('/page/edit/{slug}', 'PageController@edit', 'user');

$router->get("/login",  "AuthController@login");
$router->post("/login", "AuthController@login");
$router->get("/logout",  "AuthController@logout", "user");

$router->get('/admin/dashboard', 'AdminController@dashboard', 'admin');
$router->post('/admin/edit-template-structure', 'AdminController@editTemplateStructure', 'admin');
$router->post('/admin/delete-page', 'AdminController@deletePage', 'admin');
$router->post('/admin/delete-user', 'AdminController@deleteUser', 'admin');

$router->route($_SERVER['REQUEST_URI']);