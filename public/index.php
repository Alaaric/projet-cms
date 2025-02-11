<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define a base path for including files
$baseDir = __DIR__ . '/../src/';

$do = 'home';

if (isset($_GET['do'])) {
  $do = $_GET['do'];
}

switch ($do) {
  case 'home':
    require $baseDir . 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
    break;
  case 'debug':
    require $baseDir . 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->debug();
    break;
  default:
    http_response_code(404);
    echo 'Page not found!';
    break;
}
